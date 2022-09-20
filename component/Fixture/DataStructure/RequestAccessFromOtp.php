<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromOtp as EntityRequestAccessFromOtp;
use Phant\Auth\Domain\DataStructure\Value\{
	Otp,
	RequestAccessState,
};

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class RequestAccessFromOtp
{
	public static function get(?RequestAccessState $state = null, int $numberOfAttemptsLimit = 3, int $lifetime = 900): EntityRequestAccessFromOtp
	{
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		
		return new EntityRequestAccessFromOtp(
			FixtureApplication::get(),
			FixtureUser::get(),
			$numberOfAttemptsLimit,
			$lifetime
		);
	}
	
	public static function getExpired(?RequestAccessState $state = null, int $numberOfAttemptsLimit = 3): EntityRequestAccessFromOtp
	{
		return self::get($state, $numberOfAttemptsLimit, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromOtp
	{
		return (self::get())
			->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
