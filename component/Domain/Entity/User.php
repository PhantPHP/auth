<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\User\{
    EmailAddress,
    Firstname,
    Lastname,
    Role,
};

final class User
{
    public function __construct(
        public readonly ?EmailAddress $emailAddress,
        public readonly ?Lastname $lastname,
        public readonly ?Firstname $firstname,
        public readonly ?Role $role
    ) {
    }

    public static function make(
        ?string $emailAddress,
        ?string $lastname,
        ?string $firstname,
        ?Role $role = null
    ): self {
        if (is_string($emailAddress)) {
            $emailAddress = new EmailAddress($emailAddress);
        }
        if (is_string($lastname)) {
            $lastname = new Lastname($lastname);
        }
        if (is_string($firstname)) {
            $firstname = new Firstname($firstname);
        }

        return new self(
            $emailAddress,
            $lastname,
            $firstname,
            $role
        );
    }
}
