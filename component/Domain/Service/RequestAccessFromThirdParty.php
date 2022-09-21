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
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	Id,
	State,
	Token,
};

final class RequestAccessFromThirdParty
{
	const LIFETIME = 900; // 15 min
	
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
	
	public function generate(Application $application, int $lifetime = self::LIFETIME): Token
	{
		$requestAccess = $this->build($application, $lifetime);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function setStatus(Token $requestAccessToken, User $user, bool $isAuthorized): void
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$requestAccess->setUser($user);
		$requestAccess->setState(new State($isAuthorized ? State::VERIFIED : State::REFUSED));
		
		$this->serviceRequestAccess->set($requestAccess);
	}
	
	public function getAccessToken(Token $requestAccessToken): ?AccessToken
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$accessToken = $this->serviceAccessToken->getFromToken($requestAccess);
		
		return $accessToken;
	}
	
	private function build(Application $application, int $lifetime): EntityRequestAccessFromThirdParty
	{
		return new EntityRequestAccessFromThirdParty(
			$application,
			$lifetime
		);
	}
}
