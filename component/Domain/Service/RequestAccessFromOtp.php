<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
	AccessToken as ServiceAccessToken,
	RequestAccess as ServiceRequestAccess,
};

use Phant\Auth\Domain\Port\OtpSender;
use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	Application,
	RequestAccessFromOtp as EntityRequestAccessFromOtp,
	User,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	Id,
	Otp,
	State,
	Token,
};
use Phant\Error\NotAuthorized;

final class RequestAccessFromOtp
{
	const LIFETIME = 900; // 15 min
	
	protected ServiceRequestAccess $serviceRequestAccess;
	protected ServiceAccessToken $serviceAccessToken;
	protected OtpSender $otpSender;
	
	public function __construct(
		ServiceRequestAccess $serviceRequestAccess,
		ServiceAccessToken $serviceAccessToken,
		OtpSender $otpSender
	)
	{
		$this->serviceRequestAccess = $serviceRequestAccess;
		$this->serviceAccessToken = $serviceAccessToken;
		$this->otpSender = $otpSender;
	}
	
	public function generate(Application $application, User $user, int $numberOfAttemptsLimit = 3, int $lifetime = self::LIFETIME): Token
	{
		$requestAccess = $this->build($application, $user, $numberOfAttemptsLimit, $lifetime);
		
		$requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);
		
		$this->otpSender->send($requestAccessToken, $requestAccess, $requestAccess->getOtp());
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return $requestAccessToken;
	}
	
	public function verify(string|Token $requestAccessToken, string|Otp $otp): bool
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		if ( ! $requestAccess->canBeSetStateTo(new State(State::VERIFIED)) 
		||	 ! $requestAccess->canBeSetStateTo(new State(State::REFUSED)) ) {
			throw new NotAuthorized('The verification is not authorized');
		}
		
		if (is_string($otp)) $otp = new Otp($otp);
		
		if ( ! $requestAccess->checkOtp($otp)) {
			
			if ( ! $requestAccess->getNumberOfRemainingAttempts()) {
				$requestAccess->setState(new State(State::REFUSED));
			}
			
			$this->serviceRequestAccess->set($requestAccess);
			
			return false;
		}
		
		$requestAccess->setState(new State(State::VERIFIED));
		
		$this->serviceRequestAccess->set($requestAccess);
		
		return true;
	}
	
	public function getNumberOfRemainingAttempts(string|Token $requestAccessToken): int
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		return $requestAccess->getNumberOfRemainingAttempts($requestAccess);
	}
	
	public function getAccessToken(string|Token $requestAccessToken): ?AccessToken
	{
		if (is_string($requestAccessToken)) $requestAccessToken = new Token($requestAccessToken);
		
		$requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);
		
		$accessToken = $this->serviceAccessToken->getFromToken($requestAccess);
		
		return $accessToken;
	}
	
	private function build(Application $application, User $user, int $numberOfAttemptsLimit, int $lifetime): EntityRequestAccessFromOtp
	{
		return new EntityRequestAccessFromOtp(
			$application,
			$user,
			$numberOfAttemptsLimit,
			$lifetime
		);
	}
}
