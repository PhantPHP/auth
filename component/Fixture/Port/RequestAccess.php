<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess as EntityRequestAccess;
use Phant\Auth\Domain\DataStructure\Value\IdRequestAccess;

use Psr\SimpleCache\CacheInterface;
use Phant\Auth\Fixture\DataStructure\RequestAccessFromOtp as FixtureRequestAccessFromOtp;

use Phant\Error\NotFound;

final class RequestAccess implements \Phant\Auth\Domain\Port\RequestAccess
{
	protected CacheInterface $cache;
	
	public function __construct(CacheInterface $cache)
	{
		$this->cache = $cache;
	}
	
	public function set(EntityRequestAccess $requestAccess): void
	{
		$this->cache->set((string)$requestAccess->getId(), $requestAccess);
	}
	
	public function get(IdRequestAccess $id): EntityRequestAccess
	{
		$entity = $this->cache->get((string)$id);
		if ($entity) return $entity;
		
		$entity = FixtureRequestAccessFromOtp::get();
		
		if ((string)$entity->getId() == (string)$id) {
			throw $entity;
		}
		
		throw new NotFound('Request access not found from Id : ' . $id);
	}
}
