<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess as EntityRequestAccess;
use Phant\Auth\Domain\DataStructure\RequestAccess\Id;

interface RequestAccess
{
	public function set(EntityRequestAccess $requestAccess);
	public function get(Id $id): EntityRequestAccess;
}
