<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
	AccessToken as ServiceAccessToken,
	RequestAccess as ServiceRequestAccess,
};

use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	Application,
	RequestAccessFromThirdParty as EntityRequestAccessFromThirdParty,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	RequestAccessState,
	RequestAccessToken,
};

final class RequestAccessFromThirdParty
{
	protected ServiceRequestAccess $serviceRequestAccess;
	protected ServiceAccessToken $serviceAccessToken;
	
	public function __construct(
		ServiceRequestAccess $serviceRequestAccess,
		ServiceAccessToken $serviceAccessToken
	)
	{
		$this->serviceRequestAccess = $serviceRequestAccess;
		$this->serviceAccessToken = $serviceAccessToken;
	}
	
	public function generate(Application $application): RequestAccessToken
	{
		$requestAccess = $this->build($application);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function verify(RequestAccessToken $requestAccessToken, User $user, bool $isAuthorized): void
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$requestAccess->setUser($user);
		$requestAccess->setState(new RequestAccessState($isAuthorized ? RequestAccessState::VERIFIED : RequestAccessState::REFUSED));
		
		$this->serviceRequestAccess->set($requestAccess);
	}
	
	public function getAccessToken(RequestAccessToken $requestAccessToken): ?AccessToken
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$accessToken = $this->serviceAccessToken->getFromRequestAccessToken($requestAccess);
		
		return $accessToken;
	}
	
	private function build(Application $application): EntityRequestAccessFromThirdParty
	{
		return new EntityRequestAccessFromThirdParty(
			$application
		);
	}
}
