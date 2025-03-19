<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\RequestAccessFromApiKey as ServiceRequestAccessFromApiKey;
use Phant\Cache\File as SimpleCache;
use Phant\Auth\Fixture\Service\{
    AccessToken as FixtureServiceAccessToken,
    RequestAccess as FixtureServiceRequestAccess,
};
use Phant\Auth\Fixture\Port\Application as FixtureRepositoryApplication;

final class RequestAccessFromApiKey
{
    public function __invoke(): ServiceRequestAccessFromApiKey
    {
        return new ServiceRequestAccessFromApiKey(
            (new FixtureServiceRequestAccess())(),
            (new FixtureServiceAccessToken())(),
            new FixtureRepositoryApplication(
                new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'application')
            )
        );
    }
}
