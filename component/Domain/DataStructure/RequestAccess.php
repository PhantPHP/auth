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

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

abstract class RequestAccess extends \Phant\DataStructure\Abstract\Entity
{
	const TOKEN_PAYLOAD_EXPIRATION = 'expiration';
	const TOKEN_PAYLOAD_ID = 'request_access_id';
	
	protected IdRequestAccess $id;
	protected ?Application $application;
	protected ?User $user;
	protected AuthMethod $authMethod;
	protected RequestAccessState $state;
	protected int $expiration;
	
	public function __construct(
		IdRequestAccess $id,
		?Application $application,
		?User $user,
		AuthMethod $authMethod,
		RequestAccessState $state,
		int $lifetime
	)
	{
		$this->id = $id;
		$this->application = $application;
		$this->user = $user;
		$this->authMethod = $authMethod;
		$this->state = $state;
		$this->expiration = time() + $lifetime;
	}
	
	public function getId(): IdRequestAccess
	{
		return $this->id;
	}
	
	public function setApplication(Application $application): void
	{
		if ($this->application) {
			throw new NotAuthorized('It is not allowed to modify the application');
		}
		
		$this->application = $application;
	}
	
	public function getApplication(): ?Application
	{
		return $this->application;
	}
	
	public function setUser(User $user): void
	{
		if ($this->user) {
			throw new NotAuthorized('It is not allowed to modify the user');
		}
		
		$this->user = $user;
	}
	
	public function getUser(): ?User
	{
		return $this->user;
	}
	
	public function getAuthMethod(): AuthMethod
	{
		return $this->authMethod;
	}
	
	public function canBeSetStateTo(RequestAccessState $state): bool
	{
		return $this->state->canBeSetTo($state);
	}
	
	public function setState(RequestAccessState $state): self
	{
		if (!$this->state->canBeSetTo($state)) {
			throw new NotAuthorized('State can be set to set to : ' . $state);
		}
		
		$this->state = $state;
		
		return $this;
	}
	
	public function getState(): RequestAccessState
	{
		return $this->state;
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
