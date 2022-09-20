<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
	AccessToken as ServiceAccessToken,
	RequestAccess as ServiceRequestAccess,
};

use Phant\Auth\Domain\Port\UserNotification;
use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	Application,
	RequestAccessFromOtp as EntityRequestAccessFromOtp,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	Otp,
	RequestAccessState,
	RequestAccessToken,
};

final class RequestAccessFromOtp
{
	protected ServiceRequestAccess $serviceRequestAccess;
	protected ServiceAccessToken $serviceAccessToken;
	protected UserNotification $userNotification;
	
	public function __construct(
		ServiceRequestAccess $serviceRequestAccess,
		ServiceAccessToken $serviceAccessToken,
		UserNotification $userNotification
	)
	{
		$this->serviceRequestAccess = $serviceRequestAccess;
		$this->serviceAccessToken = $serviceAccessToken;
		$this->userNotification = $userNotification;
	}
	
	public function generate(User $user, Application $application, int $numberOfAttemptsLimit = 3): RequestAccessToken
	{
		$otp = Otp::generate();
		
		$requestAccess = new EntityRequestAccessFromOtp(
			IdRequestAccess::generate(),
			$application,
			new RequestAccessState(RequestAccessState::REQUESTED),
			$user,
			$otp,
			$numberOfAttemptsLimit
		);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->userNotification->sendOtpFromRequestAccess($requestAccessToken, $requestAccess, $otp);
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function verify(RequestAccessToken $requestAccessToken, string|Otp $otp): bool
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		if (is_string($otp)) $otp = new Otp($otp);
		
		if ( ! $requestAccess->checkOtp($otp)) {
			
			$requestAccess->setState(new RequestAccessState(RequestAccessState::REFUSED));
			
			return false;
		}
		
		$requestAccess->setState(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return true;
	}
	
	public function getNumberOfRemainingAttempts(RequestAccessToken $requestAccessToken): int
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		return $requestAccess->getNumberOfRemainingAttempts($requestAccess);
	}
	
	public function getAccessToken(RequestAccessToken $requestAccessToken): ?AccessToken
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$accessToken = $this->serviceAccessToken->getFromRequestAccessToken($requestAccess);
		
		return $accessToken;
	}
}
