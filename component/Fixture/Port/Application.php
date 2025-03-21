<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Port;

use Phant\Auth\Domain\Entity\Application as EntityApplication;
use Phant\Auth\Domain\Entity\Application\{
    ApiKey,
    Collection,
    Id,
};
use Psr\SimpleCache\CacheInterface;
use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Error\NotFound;

final class Application implements \Phant\Auth\Domain\Port\Application
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function set(EntityApplication $application): void
    {
        $this->cache->set((string)$application->id, $application);
    }

    public function get(Id $id): EntityApplication
    {
        $entity = $this->cache->get((string)$id);
        if ($entity) {
            return $entity;
        }

        foreach (FixtureApplication::getCollection()->iterate() as $entity) {
            if ((string)$entity->id != (string)$id) {
                continue;
            }

            return $entity;
        }

        throw new NotFound('Application not found from Id : ' . $id);
    }

    public function getFromApiKey(ApiKey $apiKey): EntityApplication
    {
        foreach (FixtureApplication::getCollection()->iterate() as $entity) {
            if ((string)$entity->apiKey != (string)$apiKey) {
                continue;
            }

            return $entity;
        }

        throw new NotFound('Application not found from API key : ' . $apiKey);
    }

    public function getList(): Collection
    {
        return FixtureApplication::getCollection();
    }
}
