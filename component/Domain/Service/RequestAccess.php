<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Port\RequestAccess as PortRequestAccess;
use Phant\Auth\Domain\Entity\{
    RequestAccess as EntityRequestAccess,
    SslKey,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    Id,
    Token,
};

final class RequestAccess
{
    public function __construct(
        protected readonly PortRequestAccess $repository,
        protected readonly SslKey $sslKey
    ) {
    }

    public function set(
        EntityRequestAccess $requestAccess
    ): void {
        $this->repository->set($requestAccess);
    }

    public function get(
        string|Id $id
    ): EntityRequestAccess {
        if (is_string($id)) {
            $id = new Id($id);
        }

        return $this->repository->get($id);
    }

    public function getToken(
        EntityRequestAccess $requestAccess
    ): Token {
        return $requestAccess->tokenizeId($this->sslKey);
    }

    public function getFromToken(
        string|Token $token
    ): EntityRequestAccess {
        if (is_string($token)) {
            $token = new Token($token);
        }

        $id = EntityRequestAccess::untokenizeId($token, $this->sslKey);

        return $this->get($id);
    }
}
