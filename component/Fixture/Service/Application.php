<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\Application as ServiceApplication;

use Phant\Cache\File as SimpleCache;
use Phant\Auth\Fixture\Port\Application as FixtureRepositoryApplication;

final class Application
{
    public function __invoke(): ServiceApplication
    {
        return new ServiceApplication(
            new FixtureRepositoryApplication(
                new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'application')
            )
        );
    }
}
