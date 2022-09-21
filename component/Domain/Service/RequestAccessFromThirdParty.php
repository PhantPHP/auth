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
	CallbackUrl,
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
	
	public function generate(Application $application, string|CallbackUrl $callbackUrl, int $lifetime = self::LIFETIME): Token
	{
		if (is_string($callbackUrl)) $callbackUrl = new CallbackUrl($callbackUrl);
		
		$requestAccess = $this->build($application, $callbackUrl, $lifetime);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function setStatus(string|Token $requestAccessToken, User $user, bool $isAuthorized): void
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$requestAccess->setUser($user);
		$requestAccess->setState(new State($isAuthorized ? State::VERIFIED : State::REFUSED));
		
		$this->serviceRequestAccess->set($requestAccess);
	}
	
	public function getCallbackUrl(string|Token $requestAccessToken): CallbackUrl
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		return $requestAccess->getCallbackUrl();
	}
	
	public function getAccessToken(string|Token $requestAccessToken): ?AccessToken
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$accessToken = $this->serviceAccessToken->getFromToken($requestAccess);
		
		return $accessToken;
	}
	
	private function build(Application $application, CallbackUrl 			$callbackUrl, int $lifetime): EntityRequestAccessFromThirdParty
	{
		return new EntityRequestAccessFromThirdParty(
			$application,
			$callbackUrl,
			$lifetime
		);
	}
}
