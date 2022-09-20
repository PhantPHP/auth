<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromThirdParty as EntityRequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	RequestAccessState,
};

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class RequestAccessFromThirdParty
{
	public static function get(?IdRequestAccess $id = null, ?RequestAccessState $state = null, int $lifetime = null): EntityRequestAccessFromThirdParty
	{
		if (is_null($id)) $id = new IdRequestAccess('2362ecd5-ac3b-4806-817a-966eaaf308f0');
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		if (is_null($lifetime)) $lifetime = EntityRequestAccessFromThirdParty::LIFETIME;
		
		return new EntityRequestAccessFromThirdParty(
			$id,
			FixtureApplication::get(),
			$state,
			null,
			$lifetime
		);
	}
	
	public static function getExpired(): EntityRequestAccessFromThirdParty
	{
		return self::get(null, null, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromThirdParty
	{
		return (self::get())
			->setUser(FixtureUser::get())
			->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
