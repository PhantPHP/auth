<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity\RequestAccess;

enum AuthMethod: string
{
    case ApiKey = 'api_key';

    case Otp = 'otp';

    case ThirdParty = 'broker';

    public function getLabel(): string
    {
        return match ($this) {
            self::ApiKey => 'API key',
            self::Otp => 'Otp',
            self::ThirdParty => 'Third party',
        };
    }

    public function is(self|array $typeList): bool
    {
        return is_array($typeList) ? in_array($this, $typeList) : $this == $typeList;
    }
}
