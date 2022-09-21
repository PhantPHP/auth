<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\DataStructure\Application as EntityApplication;
use Phant\Auth\Domain\DataStructure\Application\{
	ApiKey,
	Collection,
	Id,
};

interface Application
{
	public function set(EntityApplication $application): void;
	public function get(Id $id): EntityApplication;
	public function getFromApiKey(ApiKey $apiKey): EntityApplication;
	public function getList(): Collection;
}
