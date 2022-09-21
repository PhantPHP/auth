<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;

use Phant\Auth\Domain\DataStructure\{
	Application,
	AccessToken as EntityAccessToken,
	RequestAccess,
	SslKey,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\State;

use Phant\Error\NotAuthorized;

final class AccessToken
{
	public const LIFETIME = 86400; // 24h
	
	protected SslKey $sslKey;
	protected ServiceRequestAccess $serviceRequestAccess;
	
	public function __construct(
		SslKey $sslKey,
		ServiceRequestAccess $serviceRequestAccess
	)
	{
		$this->sslKey = $sslKey;
		$this->serviceRequestAccess = $serviceRequestAccess;
	}
	
	public function getPublicKey(): string
	{
		return $this->sslKey->getPublic();
	}
	
	public function check(string $accessToken, Application $application, int $lifetime = self::LIFETIME): bool
	{
		return (new EntityAccessToken($accessToken, $lifetime))->check(
			$this->sslKey, 
			$application
		);
	}
	
	public function getPayload(string $accessToken, int $lifetime = self::LIFETIME): ?array
	{
		return (new EntityAccessToken($accessToken, $lifetime))->getPayload($this->sslKey);
	}
	
	public function getFromToken(RequestAccess $requestAccess, int $lifetime = self::LIFETIME): EntityAccessToken
	{
		// Check request access status
		if (!$requestAccess->canBeSetStateTo(new State(State::GRANTED))) {
			throw new NotAuthorized('The access request is invalid');
		}
		
		// Generate new access token
		$accessToken = EntityAccessToken::generate(
			$this->sslKey,
			$requestAccess->getAuthMethod(),
			$requestAccess->getApplication(),
			$requestAccess->getUser(),
			$lifetime
		);
		
		// Change state
		$this->serviceRequestAccess->set(
			$requestAccess
				->setState(new State(State::GRANTED))
		);
		
		return $accessToken;
	}
}
