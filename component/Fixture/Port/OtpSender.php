<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Port;

use Phant\Auth\Domain\Entity\RequestAccess;
use Psr\SimpleCache\CacheInterface;
use Phant\Auth\Domain\Entity\RequestAccess\{
    Otp,
    Token,
};

final class OtpSender implements \Phant\Auth\Domain\Port\OtpSender
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function send(Token $requestAccessToken, RequestAccess $requestAccess, Otp $otp): void
    {
        $this->cache->set((string)$requestAccessToken, $otp);
    }
}
