<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\Entity\RequestAccess;
use Phant\Auth\Domain\Entity\RequestAccess\{
    Otp,
    Token,
};

interface OtpSender
{
    public function send(Token $requestAccessToken, RequestAccess $requestAccess, Otp $otp): void;
}
