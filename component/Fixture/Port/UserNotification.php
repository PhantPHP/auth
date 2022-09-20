<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess;
use Psr\SimpleCache\CacheInterface;
use Phant\Auth\Domain\DataStructure\Value\{
	Otp,
	RequestAccessToken,
};

final class UserNotification implements \Phant\Auth\Domain\Port\UserNotification
{
	protected CacheInterface $cache;
	
	public function __construct(CacheInterface $cache)
	{
		$this->cache = $cache;
	}
	
	public function sendOtpFromRequestAccess(RequestAccessToken $requestAccessToken, RequestAccess $requestAccess, Otp $otp): void
	{
		$this->cache->set((string)$requestAccessToken, $otp);
	}
}
