<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromThirdParty as EntityRequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	CallbackUrl,
	State,
};

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class RequestAccessFromThirdParty
{
	public static function get(?State $state = null, int $lifetime = 900): EntityRequestAccessFromThirdParty
	{
		if (is_null($state)) $state = new State(State::REQUESTED);
		
		return new EntityRequestAccessFromThirdParty(
			FixtureApplication::get(),
			new CallbackUrl('https://domain.ext/path'),
			$lifetime
		);
	}
	
	public static function getExpired(?State $state = null): EntityRequestAccessFromThirdParty
	{
		return self::get($state, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromThirdParty
	{
		return (self::get())
			->setUser(FixtureUser::get())
			->setState(new State(State::VERIFIED));
	}
}
