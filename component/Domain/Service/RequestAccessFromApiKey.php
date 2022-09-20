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
use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	IdRequestAccess,
	RequestAccessState,
	RequestAccessToken,
};

final class RequestAccessFromApiKey
{
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
	
	public function getAccessToken(string|ApiKey $apiKey): ?AccessToken
	{
		if (is_string($apiKey)) $apiKey = new ApiKey($apiKey);
		
		$requestAccess = $this->generate($apiKey);
		
		$application = $this->repositoryApplication->getFromApiKey($apiKey);
		
		if ( ! $application) return null;
		
		if ( ! $application->isHisApiKey($apiKey)) return null;
		
		$requestAccess->setApplication($application);
		
		$requestAccess->setState(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccess);
		
		$accessToken = $this->serviceAccessToken->getFromRequestAccessToken($requestAccess);
		
		return $accessToken;
	}
	
	private function generate(ApiKey $apiKey): EntityRequestAccessFromApiKey
	{
		return new EntityRequestAccessFromApiKey(
			IdRequestAccess::generate(),
			null,
			new RequestAccessState(RequestAccessState::REQUESTED),
			$apiKey
		);
	}
}
