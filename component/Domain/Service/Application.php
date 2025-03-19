<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Port\Application as PortApplication;
use Phant\Auth\Domain\Entity\Application as EntityApplication;
use Phant\Auth\Domain\Entity\Application\{
    ApiKey,
    Name,
    Id,
    Logo,
};

final class Application
{
    public function __construct(
        protected readonly PortApplication $repository
    ) {
    }

    public function add(
        string $name,
        ?string $logo = null
    ): EntityApplication {
        $application = new EntityApplication(
            Id::generate(),
            new Name($name),
            $logo ? new Logo($logo) : null,
            ApiKey::generate()
        );

        $this->repository->set($application);

        return $application;
    }

    public function set(
        EntityApplication $application
    ): void {
        $this->repository->set($application);
    }

    public function get(
        string|Id $id
    ): EntityApplication {
        if (is_string($id)) {
            $id = new Id($id);
        }

        return $this->repository->get($id);
    }

    public function getFromApiKey(
        string|ApiKey $apiKey
    ): EntityApplication {
        if (is_string($apiKey)) {
            $apiKey = new ApiKey($apiKey);
        }

        return $this->repository->getFromApiKey($apiKey);
    }
}
