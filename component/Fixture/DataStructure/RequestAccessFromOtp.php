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
	public static function get(?RequestAccessState $state = null, int $lifetime = null): EntityRequestAccessFromOtp
	{
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		if (is_null($lifetime)) $lifetime = EntityRequestAccessFromOtp::LIFETIME;
		$numberOfAttemptsLimit = 3;
		
		return new EntityRequestAccessFromOtp(
			FixtureApplication::get(),
			FixtureUser::get(),
			$numberOfAttemptsLimit,
			$lifetime
		);
	}
	
	public static function getExpired(?RequestAccessState $state = null): EntityRequestAccessFromOtp
	{
		return self::get($state, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromOtp
	{
		return (self::get())
			->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
