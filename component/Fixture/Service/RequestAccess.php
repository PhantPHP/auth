<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;

use Phant\Cache\SimpleCache;
use Phant\Auth\Fixture\DataStructure\SslKey as FixtureSslKey;
use Phant\Auth\Fixture\Port\RequestAccess as FixtureRepositoryRequestAccess;

final class RequestAccess
{
    public function __invoke(): ServiceRequestAccess
    {
        return new ServiceRequestAccess(
            new FixtureRepositoryRequestAccess(
                new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'request-access')
            ),
            FixtureSslKey::get()
        );
    }
}
