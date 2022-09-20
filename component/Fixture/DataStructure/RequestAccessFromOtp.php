<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	RequestAccessFromOtp as EntityRequestAccessFromOtp,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
	IdRequestAccess,
	Jwt,
	Otp,
	RequestAccessState,
};

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class RequestAccessFromOtp
{
	public static function get(?IdRequestAccess $id = null, ?RequestAccessState $state = null, int $lifetime = null): EntityRequestAccessFromOtp
	{
		if (is_null($id)) $id = new IdRequestAccess('2362ecd5-ac3b-4806-817a-966eaaf308f0');
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		if (is_null($lifetime)) $lifetime = EntityRequestAccessFromOtp::LIFETIME;
		
		return new EntityRequestAccessFromOtp(
			$id,
			FixtureApplication::get(),
			$state,
			FixtureUser::get(),
			new Otp('123456'),
			$lifetime
		);
	}
	
	public static function getExpired(): EntityRequestAccessFromOtp
	{
		return self::get(null, null, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromOtp
	{
		return (self::get())->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
