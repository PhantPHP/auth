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
	
	public function generate(User $user, Application $application): RequestAccessToken
	{
		$otp = Otp::generate();
		
		$requestAccess = new EntityRequestAccessFromOtp(
			IdRequestAccess::generate(),
			$application,
			new RequestAccessState(RequestAccessState::REQUESTED),
			$user,
			$otp
		);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->userNotification->sendOtpFromRequestAccess($requestAccessToken, $requestAccess, $otp);
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function getAccessToken(RequestAccessToken $requestAccessToken, string|Otp $otp): ?AccessToken
	{
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		if (is_string($otp)) $otp = new Otp($otp);
		
		if ( ! $requestAccess->checkOtp($otp)) return null;
		
		$requestAccess->setState(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccess);
		
		$accessToken = $this->serviceAccessToken->getFromRequestAccessToken($requestAccess);
		
		return $accessToken;
	}
}
