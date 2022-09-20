<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\RequestAccessFromOtp as ServiceRequestAccessFromOtp;

use Phant\Auth\Fixture\Port\UserNotification as FixtureRepositoryUserNotification;
use Phant\Auth\Fixture\Service\{
	AccessToken as FixtureServiceAccessToken,
	RequestAccess as FixtureServiceRequestAccess,
};
use Phant\Cache\SimpleCache;

final class RequestAccessFromOtp
{
	public function __invoke(): ServiceRequestAccessFromOtp
	{
		return new ServiceRequestAccessFromOtp(
			(new FixtureServiceRequestAccess())(),
			(new FixtureServiceAccessToken())(),
			new FixtureRepositoryUserNotification(
				new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'user-notification')
			)
		);
	}
}
