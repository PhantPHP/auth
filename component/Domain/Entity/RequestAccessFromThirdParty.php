<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\{
    Application,
    User,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    AuthMethod,
    CallbackUrl,
    Id,
    State,
};

final class RequestAccessFromThirdParty extends \Phant\Auth\Domain\Entity\RequestAccess
{
    public function __construct(
        Application $application,
        public readonly CallbackUrl $callbackUrl,
        int $lifetime
    ) {
        parent::__construct(
            Id::generate(),
            $application,
            null,
            AuthMethod::ThirdParty,
            State::Requested,
            $lifetime
        );
    }
}
