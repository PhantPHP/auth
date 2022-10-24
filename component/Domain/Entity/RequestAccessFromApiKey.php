<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\Application;
use Phant\Auth\Domain\Entity\Application\ApiKey;
use Phant\Auth\Domain\Entity\RequestAccess\{
    AuthMethod,
    Id,
    State,
};

use Phant\Error\NotCompliant;

final class RequestAccessFromApiKey extends \Phant\Auth\Domain\Entity\RequestAccess
{
    public function __construct(
        protected readonly ApiKey $apiKey,
        int $lifetime
    ) {
        parent::__construct(
            Id::generate(),
            null,
            null,
            AuthMethod::ApiKey,
            State::Requested,
            $lifetime
        );
    }
}
