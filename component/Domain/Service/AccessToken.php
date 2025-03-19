<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;
use Phant\Auth\Domain\Entity\{
    Application,
    AccessToken as EntityAccessToken,
    RequestAccess,
    SslKey,
};
use Phant\Auth\Domain\Entity\RequestAccess\State;
use Phant\Error\NotAuthorized;

final class AccessToken
{
    public const LIFETIME = 86400; // 24h

    public function __construct(
        protected readonly SslKey $sslKey,
        protected readonly ServiceRequestAccess $serviceRequestAccess
    ) {
    }

    public function getPublicKey(): string
    {
        return $this->sslKey->public;
    }

    public function check(
        string $accessToken,
        Application $application
    ): bool {
        return (new EntityAccessToken($accessToken))->check(
            $this->sslKey,
            $application
        );
    }

    public function getPayload(
        string $accessToken
    ): ?array {
        return (new EntityAccessToken($accessToken))->getPayload($this->sslKey);
    }

    public function getFromToken(
        RequestAccess $requestAccess,
        int $lifetime = self::LIFETIME
    ): EntityAccessToken {
        // Check request access status
        if (!$requestAccess->canBeSetStateTo(State::Granted)) {
            throw new NotAuthorized('The access request is invalid');
        }

        // Generate new access token
        $accessToken = EntityAccessToken::generate(
            $this->sslKey,
            $requestAccess->authMethod,
            $requestAccess->application,
            $requestAccess->user,
            $lifetime
        );

        // Change state
        $this->serviceRequestAccess->set(
            $requestAccess
                ->setState(State::Granted)
        );

        return $accessToken;
    }
}
