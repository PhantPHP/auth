<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromThirdParty as EntityRequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\Value\RequestAccessState;

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class RequestAccessFromThirdParty
{
	public static function get(?RequestAccessState $state = null, int $lifetime = null): EntityRequestAccessFromThirdParty
	{
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		if (is_null($lifetime)) $lifetime = EntityRequestAccessFromThirdParty::LIFETIME;
		
		return new EntityRequestAccessFromThirdParty(
			FixtureApplication::get(),
			$lifetime
		);
	}
	
	public static function getExpired(?RequestAccessState $state = null): EntityRequestAccessFromThirdParty
	{
		return self::get($state, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromThirdParty
	{
		return (self::get())
			->setUser(FixtureUser::get())
			->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
