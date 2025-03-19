<?php

declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\RequestAccessFromOtp as ServiceRequestAccessFromOtp;
use Phant\Auth\Fixture\Port\OtpSender as FixtureRepositoryOtpSender;
use Phant\Auth\Fixture\Service\{
    AccessToken as FixtureServiceAccessToken,
    RequestAccess as FixtureServiceRequestAccess,
};
use Phant\Cache\File as SimpleCache;

final class RequestAccessFromOtp
{
    public function __invoke(): ServiceRequestAccessFromOtp
    {
        return new ServiceRequestAccessFromOtp(
            (new FixtureServiceRequestAccess())(),
            (new FixtureServiceAccessToken())(),
            new FixtureRepositoryOtpSender(
                new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'user-notification')
            )
        );
    }
}
