<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\Value;

use Phant\Auth\Domain\DataStructure\Application;

final class CollectionApplication extends \Phant\DataStructure\Abstract\Collection
{
	public function addApplication(Application $entity)
	{
		parent::addItem($entity);
	}
	
	public function searchByApiKey(string|ApiKey $apiKey): ?Application
	{
		if (is_string($apiKey)) $apiKey = new ApiKey($apiKey);
		
		foreach ($this->itemsIterator() as $entity) {
			if ($entity->isHisApiKey($apiKey)) return $entity;
		}
		
		return null;
	}
}
