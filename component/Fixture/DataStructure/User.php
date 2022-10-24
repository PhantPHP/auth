<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\Entity\User as EntityUser;
use Phant\Auth\Domain\Entity\User\{
    EmailAddress,
    Firstname,
    Lastname,
    Role,
};

final class User
{
    public static function get(): EntityUser
    {
        return new EntityUser(
            new EmailAddress('john.doe@domain.ext'),
            new Lastname('DOE'),
            new Firstname('John'),
            null
        );
    }
}
