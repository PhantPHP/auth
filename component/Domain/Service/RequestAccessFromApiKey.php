<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
    AccessToken as ServiceAccessToken,
    RequestAccess as ServiceRequestAccess,
};
use Phant\Auth\Domain\Port\Application as PortApplication;
use Phant\Auth\Domain\Entity\{
    AccessToken,
    Application,
    RequestAccessFromApiKey as EntityRequestAccessFromApiKey,
    User,
};
use Phant\Auth\Domain\Entity\Application\ApiKey;
use Phant\Auth\Domain\Entity\RequestAccess\{
    Id,
    State,
    Token,
};
use Phant\Error\NotFound;

final class RequestAccessFromApiKey
{
    public const LIFETIME = 300; // 5 min

    public function __construct(
        protected readonly ServiceRequestAccess $serviceRequestAccess,
        protected readonly ServiceAccessToken $serviceAccessToken,
        protected readonly PortApplication $repositoryApplication
    ) {
    }

    public function getAccessToken(
        string|ApiKey $apiKey,
        int $lifetime = self::LIFETIME
    ): ?AccessToken {
        if (is_string($apiKey)) {
            $apiKey = new ApiKey($apiKey);
        }

        $requestAccess = $this->build($apiKey, $lifetime);

        try {
            $application = $this->repositoryApplication->getFromApiKey($apiKey);
        } catch (NotFound $e) {
            throw new NotFound('Application not found');
        }

        $requestAccess->setApplication($application);

        $requestAccess->setState(State::Verified);

        $this->serviceRequestAccess->set($requestAccess);

        $accessToken = $this->serviceAccessToken->getFromToken($requestAccess);

        return $accessToken;
    }

    private function build(
        ApiKey $apiKey,
        int $lifetime
    ): EntityRequestAccessFromApiKey {
        return new EntityRequestAccessFromApiKey(
            $apiKey,
            $lifetime
        );
    }
}
