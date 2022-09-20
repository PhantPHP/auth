<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
	AccessToken as ServiceAccessToken,
	RequestAccess as ServiceRequestAccess,
};

use Phant\Auth\Domain\Port\{
	UserNotification,
};
use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	Application,
	RequestAccessFromOtp as EntityRequestAccessFromOtp,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
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
		
		$requestAccessFromOtp = new EntityRequestAccessFromOtp(
			IdRequestAccess::generate(),
			$application,
			new RequestAccessState(RequestAccessState::REQUESTED),
			$user,
			$otp
		);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccessFromOtp);
		
		$this->userNotification->sendOtpFromRequestAccess($requestAccessToken, $requestAccessFromOtp, $otp);
		
		$this->serviceRequestAccess->set($requestAccessFromOtp);
		
		return $requestAccessToken;
	}
	
	public function getAccessToken(RequestAccessToken $requestAccessToken, string|Otp $otp): ?AccessToken
	{
		$requestAccessFromOtp = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		if (is_string($otp)) $otp = new Otp($otp);
		
		if ( ! $requestAccessFromOtp->checkOtp($otp)) return null;
		
		$requestAccessFromOtp->setState(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccessFromOtp);
		
		$accessToken = $this->serviceAccessToken->getFromRequestAccessToken($requestAccessFromOtp);
		
		return $accessToken;
	}
}
