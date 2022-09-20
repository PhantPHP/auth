<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromApiKey as EntityRequestAccessFromApiKey;
use Phant\Auth\Domain\DataStructure\Value\RequestAccessState;

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class RequestAccessFromApiKey
{
	public static function get(?RequestAccessState $state = null, int $lifetime = 300): EntityRequestAccessFromApiKey
	{
		if (is_null($state)) $state = new RequestAccessState(RequestAccessState::REQUESTED);
		
		return new EntityRequestAccessFromApiKey(
			FixtureApplication::get()->apiKey,
			$lifetime
		);
	}
	
	public static function getExpired(?RequestAccessState $state = null): EntityRequestAccessFromApiKey
	{
		return self::get($state, -9999);
	}
	
	public static function getVerified(): EntityRequestAccessFromApiKey
	{
		return (self::get())
			->setApplication(FixtureApplication::get())
			->setState(new RequestAccessState(RequestAccessState::VERIFIED));
	}
}
