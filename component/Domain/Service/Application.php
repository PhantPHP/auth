<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Port\Application as PortApplication;

use Phant\Auth\Domain\DataStructure\Application as EntityApplication;
use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	ApplicationName,
	IdApplication,
	Logo,
};

final class Application
{
	protected PortApplication $repository;
	
	public function __construct(
		PortApplication $repository
	)
	{
		$this->repository = $repository;
	}
	
	public function add(string $name, ?string $logo = null): EntityApplication
	{
		$application = new EntityApplication(
			IdApplication::generate(),
			new ApplicationName($name),
			$logo ? new Logo($logo) : null,
			ApiKey::generate()
		);
		
		$this->repository->set($application);
		
		return $application;
	}
	
	public function set(EntityApplication $application): void
	{
		$this->repository->set($application);
	}
	
	public function get(string|IdApplication $id): EntityApplication
	{
		if (is_string($id)) $id = new IdApplication($id);
		
		return $this->repository->get($id);
	}
	
	public function getFromApiKey(string|ApiKey $apiKey): EntityApplication
	{
		if (is_string($apiKey)) $apiKey = new ApiKey($apiKey);
		
		return $this->repository->getFromApiKey($apiKey);
	}
}
