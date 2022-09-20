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

use Phant\Error\NotCompliant;

final class RequestAccessFromOtp extends \Phant\Auth\Domain\DataStructure\RequestAccess
{
	protected Otp $otp;
	
	public function __construct(
		IdRequestAccess $id,
		Application $application,
		RequestAccessState $state,
		User $user,
		Otp $otp,
		int $lifetime = self::LIFETIME
	)
	{
		parent::__construct(
			$id,
			$application,
			new AuthMethod(AuthMethod::OTP),
			$state,
			$user,
			$lifetime
		);
		
		$this->otp = $otp;
	}
	
	public function checkOtp(string|Otp $otp): bool
	{
		if (is_string($otp)) $otp = new Otp($otp);
		
		return $this->otp->check($otp);
	}
}
