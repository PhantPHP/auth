<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\Entity\AccessToken as EntityAccessToken;

use Phant\Auth\Fixture\DataStructure\{
    Application as FixtureApplication,
    SslKey as FixtureSslKey,
    User as FixtureUser,
};
use Phant\Auth\Domain\Entity\RequestAccess\AuthMethod;
use Phant\Auth\Domain\Entity\AccessToken\Jwt;

final class AccessToken
{
    public static function get(): EntityAccessToken
    {
        return EntityAccessToken::generate(
            FixtureSslKey::get(),
            AuthMethod::ApiKey,
            FixtureApplication::get(),
            FixtureUser::get(),
            86400
        );
    }

    public static function getWithoutUser(): EntityAccessToken
    {
        return EntityAccessToken::generate(
            FixtureSslKey::get(),
            AuthMethod::ApiKey,
            FixtureApplication::get(),
            null,
            86400
        );
    }

    public static function getExpired(): EntityAccessToken
    {
        return EntityAccessToken::generate(
            FixtureSslKey::get(),
            AuthMethod::ApiKey,
            FixtureApplication::get(),
            FixtureUser::get(),
            -9999
        );
    }

    public static function getInvalid(): EntityAccessToken
    {
        return new EntityAccessToken(
            (string)Jwt::encode(FixtureSslKey::get()->private, [], 86400)
        );
    }
}
