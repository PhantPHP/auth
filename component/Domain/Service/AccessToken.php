<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;

use Phant\Auth\Domain\DataStructure\{
	AccessToken as EntityAccessToken,
	RequestAccess,
};
use Phant\Auth\Domain\DataStructure\Value\{
	RequestAccessState,
	SslKey,
};
use Phant\Auth\Domain\DataStructure\Application;

use Phant\Error\NotAuthorized;

final class AccessToken
{
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
	
	public function getAlgorithm(): string
	{
		return EntityAccessToken::ALGORITHM;
	}
	
	public function check(string $accessToken, Application $application): bool
	{
		return (new EntityAccessToken($accessToken))->check(
			$this->sslKey, 
			$application
		);
	}
	
	public function getFromRequestAccessToken(RequestAccess $requestAccess): EntityAccessToken
	{
		// Check request access status
		if (!$requestAccess->canBeSetStateTo(new RequestAccessState(RequestAccessState::GRANTED))) {
			throw new NotAuthorized('The access request is invalid');
		}
		
		// Generate new access token
		$accessToken = EntityAccessToken::generate(
			$this->sslKey,
			$requestAccess->getApplication(),
			$requestAccess->getUser()
		);
		
		// Change state
		$this->serviceRequestAccess->set(
			$requestAccess
				->setState(new RequestAccessState(RequestAccessState::GRANTED))
		);
		
		return $accessToken;
	}
	
	public function getUserInfos(string $accessToken): ?array
	{
		$payLoad = (new EntityAccessToken($accessToken))->getPayload($this->sslKey);
		
		if (!isset($payLoad[ EntityAccessToken::PAYLOAD_KEY_USER ])) return null;
		
		return (array)$payLoad[ EntityAccessToken::PAYLOAD_KEY_USER ];
	}
}
