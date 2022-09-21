<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
	AccessToken as ServiceAccessToken,
	RequestAccess as ServiceRequestAccess,
};
use Phant\Auth\Domain\Port\Application as PortApplication;

use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	Application,
	RequestAccessFromApiKey as EntityRequestAccessFromApiKey,
	User,
};
use Phant\Auth\Domain\DataStructure\Application\ApiKey;
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	Id,
	State,
	Token,
};

final class RequestAccessFromApiKey
{
	const LIFETIME = 300; // 5 min
	
	protected ServiceRequestAccess $serviceRequestAccess;
	protected ServiceAccessToken $serviceAccessToken;
	protected PortApplication $repositoryApplication;
	
	public function __construct(
		ServiceRequestAccess $serviceRequestAccess,
		ServiceAccessToken $serviceAccessToken,
		PortApplication $repositoryApplication
	)
	{
		$this->serviceRequestAccess = $serviceRequestAccess;
		$this->serviceAccessToken = $serviceAccessToken;
		$this->repositoryApplication = $repositoryApplication;
	}
	
	public function getAccessToken(string|ApiKey $apiKey, int $lifetime = self::LIFETIME): ?AccessToken
	{
		if (is_string($apiKey)) $apiKey = new ApiKey($apiKey);
		
		$requestAccess = $this->build($apiKey, $lifetime);
		
		$application = $this->repositoryApplication->getFromApiKey($apiKey);
		
		if ( ! $application) return null;
		
		if ( ! $application->isHisApiKey($apiKey)) return null;
		
		$requestAccess->setApplication($application);
		
		$requestAccess->setState(new State(State::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccess);
		
		$accessToken = $this->serviceAccessToken->getFromToken($requestAccess);
		
		return $accessToken;
	}
	
	private function build(ApiKey $apiKey, int $lifetime): EntityRequestAccessFromApiKey
	{
		return new EntityRequestAccessFromApiKey(
			$apiKey,
			$lifetime
		);
	}
}
