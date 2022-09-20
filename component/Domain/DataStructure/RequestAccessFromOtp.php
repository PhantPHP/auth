<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
	IdRequestAccess,
	RequestAccessState,
	Otp,
};

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

final class RequestAccessFromOtp extends \Phant\Auth\Domain\DataStructure\RequestAccess
{
	protected Otp $otp;
	protected int $numberOfRemainingAttempts;
	
	public function __construct(
		IdRequestAccess $id,
		Application $application,
		RequestAccessState $state,
		User $user,
		Otp $otp,
		int $numberOfAttemptsLimit,
		int $lifetime = self::LIFETIME
	)
	{
		if ($numberOfAttemptsLimit < 1) {
			throw new NotCompliant('the number of attempts must be at least 1');
		}
		
		parent::__construct(
			$id,
			$application,
			new AuthMethod(AuthMethod::OTP),
			$state,
			$user,
			$lifetime
		);
		
		$this->otp = $otp;
		$this->numberOfRemainingAttempts = $numberOfAttemptsLimit;
	}
	
	public function getNumberOfRemainingAttempts(): int
	{
		return $this->numberOfRemainingAttempts;
	}
	
	public function checkOtp(string|Otp $otp): bool
	{
		if ($this->numberOfRemainingAttempts <= 0) {
			throw new NotAuthorized('The number of attempts is reach');
		}
		
		if (is_string($otp)) $otp = new Otp($otp);
		
		$this->numberOfRemainingAttempts--;
		
		return $this->otp->check($otp);
	}
}
