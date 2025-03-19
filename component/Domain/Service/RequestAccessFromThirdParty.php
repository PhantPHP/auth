<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Service\{
    AccessToken as ServiceAccessToken,
    RequestAccess as ServiceRequestAccess,
};
use Phant\Auth\Domain\Entity\{
    AccessToken,
    Application,
    RequestAccessFromThirdParty as EntityRequestAccessFromThirdParty,
    User,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    CallbackUrl,
    Id,
    State,
    Token,
};

final class RequestAccessFromThirdParty
{
    public const LIFETIME = 900; // 15 min

    public function __construct(
        protected readonly ServiceRequestAccess $serviceRequestAccess,
        protected readonly ServiceAccessToken $serviceAccessToken
    ) {
    }

    public function generate(
        Application $application,
        string|CallbackUrl $callbackUrl,
        int $lifetime = self::LIFETIME
    ): Token {
        if (is_string($callbackUrl)) {
            $callbackUrl = new CallbackUrl($callbackUrl);
        }

        $requestAccess = $this->build($application, $callbackUrl, $lifetime);

        $requestAccessToken = $this->serviceRequestAccess->getToken($requestAccess);

        $this->serviceRequestAccess->set($requestAccess);

        return $requestAccessToken;
    }

    public function setStatus(
        string|Token $requestAccessToken,
        User $user,
        bool $isAuthorized
    ): void {
        if (is_string($requestAccessToken)) {
            $requestAccessToken = new Token($requestAccessToken);
        }

        $requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);

        $requestAccess->setUser($user);
        $requestAccess->setState($isAuthorized ? State::Verified : State::Refused);

        $this->serviceRequestAccess->set($requestAccess);
    }

    public function getCallbackUrl(
        string|Token $requestAccessToken
    ): CallbackUrl {
        if (is_string($requestAccessToken)) {
            $requestAccessToken = new Token($requestAccessToken);
        }

        $requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);

        return $requestAccess->callbackUrl;
    }

    public function getAccessToken(
        string|Token $requestAccessToken
    ): ?AccessToken {
        if (is_string($requestAccessToken)) {
            $requestAccessToken = new Token($requestAccessToken);
        }

        $requestAccess = $this->serviceRequestAccess->getFromToken($requestAccessToken);

        $accessToken = $this->serviceAccessToken->getFromToken($requestAccess);

        return $accessToken;
    }

    private function build(
        Application $application,
        CallbackUrl $callbackUrl,
        int $lifetime
    ): EntityRequestAccessFromThirdParty {
        return new EntityRequestAccessFromThirdParty(
            $application,
            $callbackUrl,
            $lifetime
        );
    }
}
