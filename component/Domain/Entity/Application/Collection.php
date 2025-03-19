<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity\Application;

use Phant\Auth\Domain\Entity\Application;
use Phant\Auth\Domain\Entity\Application\{
    ApiKey,
    Id,
};

final class Collection extends \Phant\DataStructure\Abstract\Collection
{
    public function addApplication(Application $entity)
    {
        parent::addItem($entity);
    }

    public function searchById(string|Id $id): ?Application
    {
        if (is_string($id)) {
            $id = new Id($id);
        }

        foreach ($this->iterate() as $entity) {
            if ($entity->isHisId($id)) {
                return $entity;
            }
        }

        return null;
    }

    public function searchByApiKey(string|ApiKey $apiKey): ?Application
    {
        if (is_string($apiKey)) {
            $apiKey = new ApiKey($apiKey);
        }

        foreach ($this->iterate() as $entity) {
            if ($entity->isHisApiKey($apiKey)) {
                return $entity;
            }
        }

        return null;
    }
}
