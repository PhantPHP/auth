<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\Entity\RequestAccessFromApiKey as EntityRequestAccessFromApiKey;
use Phant\Auth\Domain\Entity\RequestAccess\State;

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class RequestAccessFromApiKey
{
    public static function get(?State $state = null, int $lifetime = 300): EntityRequestAccessFromApiKey
    {
        if (is_null($state)) {
            $state = State::Requested;
        }

        return new EntityRequestAccessFromApiKey(
            FixtureApplication::get()->apiKey,
            $lifetime
        );
    }

    public static function getExpired(?State $state = null): EntityRequestAccessFromApiKey
    {
        return self::get($state, -9999);
    }

    public static function getVerified(): EntityRequestAccessFromApiKey
    {
        return (self::get())
            ->setApplication(FixtureApplication::get())
            ->setState(State::Verified);
    }
}
