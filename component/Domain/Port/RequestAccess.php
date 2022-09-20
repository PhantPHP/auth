<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess as EntityRequestAccess;
use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	IdRequestAccess,
};

interface RequestAccess
{
	public function set(EntityRequestAccess $requestAccess);
	public function get(IdRequestAccess $id): EntityRequestAccess;
}
