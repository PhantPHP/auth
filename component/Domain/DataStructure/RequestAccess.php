<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
	IdRequestAccess,
	Jwt,
	RequestAccessState,
	RequestAccessToken,
	SslKey,
};

use Phant\Error\NotCompliant;

abstract class RequestAccess extends \Phant\DataStructure\Abstract\Entity
{
	const TOKEN_PAYLOAD_EXPIRATION = 'expiration';
	const TOKEN_PAYLOAD_ID = 'request_access_id';
	const LIFETIME = 900; // 15 min
	
	protected IdRequestAccess $id;
	protected ?Application $application;
	protected AuthMethod $authMethod;
	protected RequestAccessState $state;
	protected ?User $user;
	protected int $expiration;
	
	public function __construct(
		IdRequestAccess $id,
		?Application $application,
		AuthMethod $authMethod,
		RequestAccessState $state,
		?User $user = null,
		int $lifetime = self::LIFETIME
	)
	{
		$this->id = $id;
		$this->application = $application;
		$this->authMethod = $authMethod;
		$this->state = $state;
		$this->user = $user;
		$this->expiration = time() + $lifetime;
	}
	
	public function getId(): IdRequestAccess
	{
		return $this->id;
	}
	
	public function getApplication(): ?Application
	{
		return $this->application;
	}
	
	public function setApplication(Application $application): void
	{
		$this->application = $application;
	}
	
	public function getAuthMethod(): AuthMethod
	{
		return $this->authMethod;
	}
	
	public function getState(): RequestAccessState
	{
		return $this->state;
	}
	
	public function getUser(): ?User
	{
		return $this->user;
	}
	
	public function setUser(User $user): void
	{
		$this->user = $user;
	}
	
	public function canBeSetStateTo(RequestAccessState $state): bool
	{
		return $this->state->canBeSetTo($state);
	}
	
	public function setState(RequestAccessState $state): self
	{
		if (!$this->state->canBeSetTo($state)) {
			throw new NotCompliant('State can be set to set to : ' . $state);
		}
		
		$this->state = $state;
		
		return $this;
	}
	
	public function tokenizeId(SslKey $sslKey): RequestAccessToken
	{
		$id = (string)$this->id;
		
		$datas = json_encode([
			self::TOKEN_PAYLOAD_EXPIRATION => $this->expiration,
			self::TOKEN_PAYLOAD_ID => $id,
		]);
		
		$token = $sslKey->encrypt($datas);
		
		$token = strtr(base64_encode($token), '+/=', '._-');
		
		return new RequestAccessToken($token);
	}
	
	public static function untokenizeId(RequestAccessToken $token, SslKey $sslKey): IdRequestAccess
	{
		$token = base64_decode(strtr((string)$token, '._-', '+/='));
		
		$datas = $sslKey->decrypt($token);
		
		$datas = json_decode($datas, true);
		
		$expiration = $datas[ self::TOKEN_PAYLOAD_EXPIRATION ] ?? 0;
		if ($expiration < time()) {
			throw new NotCompliant('Token expired');
		}
		
		$id = $datas[ self::TOKEN_PAYLOAD_ID ];
		
		return new IdRequestAccess($id);
	}
}
