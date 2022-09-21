<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\AccessToken as EntityAccessToken;

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	SslKey as FixtureSslKey,
	User as FixtureUser,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\AuthMethod;

final class AccessToken
{
	public static function get(): EntityAccessToken
	{
		return EntityAccessToken::generate(
			FixtureSslKey::get(),
			new AuthMethod(AuthMethod::API_KEY),
			FixtureApplication::get(),
			FixtureUser::get(),
			86400
		);
	}
	
	public static function getExpired(): EntityAccessToken
	{
		return EntityAccessToken::generate(
			FixtureSslKey::get(),
			new AuthMethod(AuthMethod::API_KEY),
			FixtureApplication::get(),
			FixtureUser::get(),
			-9999
		);
	}
}
