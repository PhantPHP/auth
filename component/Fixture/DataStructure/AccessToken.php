<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\AccessToken as EntityAccessToken;

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	SslKey as FixtureSslKey,
	User as FixtureUser,
};

final class AccessToken
{
	public static function get(): EntityAccessToken
	{
		return EntityAccessToken::generate(
			FixtureSslKey::get(),
			FixtureApplication::get(),
			FixtureUser::get(),
			86400
		);
	}
	
	public static function getExpired(): EntityAccessToken
	{
		return EntityAccessToken::generate(
			FixtureSslKey::get(),
			FixtureApplication::get(),
			FixtureUser::get(),
			-9999
		);
	}
}
