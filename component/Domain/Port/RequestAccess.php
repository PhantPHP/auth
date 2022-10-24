<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\Entity\RequestAccess as EntityRequestAccess;
use Phant\Auth\Domain\Entity\RequestAccess\Id;

interface RequestAccess
{
    public function set(EntityRequestAccess $requestAccess);
    public function get(Id $id): EntityRequestAccess;
}
