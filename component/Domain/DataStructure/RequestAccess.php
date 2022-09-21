<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	SslKey,
	User,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	AuthMethod,
	Id,
	State,
	Token,
};

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

abstract class RequestAccess extends \Phant\DataStructure\Abstract\Entity
{
	const TOKEN_PAYLOAD_EXPIRATION = 'expiration';
	const TOKEN_PAYLOAD_ID = 'request_access_id';
	
	protected Id $id;
	protected ?Application $application;
	protected ?User $user;
	protected AuthMethod $authMethod;
	protected State $state;
	protected int $expiration;
	
	public function __construct(
		Id $id,
		?Application $application,
		?User $user,
		AuthMethod $authMethod,
		State $state,
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
	
	public function getId(): Id
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
	
	public function canBeSetStateTo(State $state): bool
	{
		return $this->state->canBeSetTo($state);
	}
	
	public function setState(State $state): self
	{
		if (!$this->state->canBeSetTo($state)) {
			throw new NotAuthorized('State can be set to set to : ' . $state);
		}
		
		$this->state = $state;
		
		return $this;
	}
	
	public function getState(): State
	{
		return $this->state;
	}
	
	public function tokenizeId(SslKey $sslKey): Token
	{
		$id = (string)$this->id;
		
		$datas = json_encode([
			self::TOKEN_PAYLOAD_EXPIRATION => $this->expiration,
			self::TOKEN_PAYLOAD_ID => $id,
		]);
		
		$token = $sslKey->encrypt($datas);
		
		$token = strtr(base64_encode($token), '+/=', '._-');
		
		return new Token($token);
	}
	
	public static function untokenizeId(Token $token, SslKey $sslKey): Id
	{
		$token = base64_decode(strtr((string)$token, '._-', '+/='));
		
		$datas = $sslKey->decrypt($token);
		
		$datas = json_decode($datas, true);
		
		$expiration = $datas[ self::TOKEN_PAYLOAD_EXPIRATION ] ?? 0;
		if ($expiration < time()) {
			throw new NotCompliant('Token expired');
		}
		
		$id = $datas[ self::TOKEN_PAYLOAD_ID ];
		
		return new Id($id);
	}
}
