<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\{
    Application,
    User,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    AuthMethod,
    Id,
    State,
    Otp,
};

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

final class RequestAccessFromOtp extends \Phant\Auth\Domain\Entity\RequestAccess
{
    public readonly Otp $otp;

    public function __construct(
        Application $application,
        User $user,
        protected int $numberOfRemainingAttempts,
        int $lifetime
    ) {
        if ($numberOfRemainingAttempts < 1) {
            throw new NotCompliant('the number of attempts must be at least 1');
        }

        $this->otp = Otp::generate();

        parent::__construct(
            Id::generate(),
            $application,
            $user,
            AuthMethod::Otp,
            State::Requested,
            $lifetime
        );
    }

    public function getNumberOfRemainingAttempts(): int
    {
        return $this->numberOfRemainingAttempts;
    }

    public function checkOtp(string|Otp $otp): bool
    {
        if ($this->numberOfRemainingAttempts <= 0) {
            throw new NotAuthorized('The number of attempts is reach');
        }

        if (is_string($otp)) {
            $otp = new Otp($otp);
        }

        $this->numberOfRemainingAttempts--;

        return $this->otp->check($otp);
    }
}
