<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\Application\{
    ApiKey,
    Name,
    Id,
    Logo,
};

final class Application
{
    public function __construct(
        public readonly Id $id,
        public readonly Name $name,
        public readonly ?Logo $logo,
        public readonly ApiKey $apiKey
    ) {
    }

    public function isHisApiKey(string|ApiKey $apiKey): bool
    {
        if (is_string($apiKey)) {
            $apiKey = new ApiKey($apiKey);
        }

        return ((string)$this->apiKey === (string)$apiKey);
    }

    public function isHisId(string|Id $id): bool
    {
        if (is_string($id)) {
            $id = new Id($id);
        }

        return ((string)$this->id === (string)$id);
    }

    public static function make(
        null|string|Id $id,
        null|string|Name $name,
        null|string|Logo $logo,
        string|ApiKey $apiKey
    ): self {
        if (is_null($id)) {
            $id = Id::generate();
        }
        if (is_string($id)) {
            $id = new Id($id);
        }
        if (is_string($name)) {
            $name = new Name($name);
        }
        if (is_string($logo)) {
            $logo = new Logo($logo);
        }
        if (is_string($apiKey)) {
            $apiKey = new ApiKey($apiKey);
        }

        return new self(
            $id,
            $name,
            $logo,
            $apiKey
        );
    }
}
