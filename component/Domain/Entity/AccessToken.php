<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\{
    Application,
    SslKey,
    User,
};
use Phant\Auth\Domain\Entity\AccessToken\{
    Expire,
    Id,
    Jwt,
};
use Phant\Auth\Domain\Entity\RequestAccess\AuthMethod;
use Phant\Auth\Domain\Serialize\{
    Application as SerializeApplication,
    User as SerializeUser,
};

final class AccessToken
{
    public const PAYLOAD_KEY_EXPIRE = 'expire';
    public const PAYLOAD_KEY_AUTH_METHOD = 'auth_method';
    public const PAYLOAD_KEY_APP = 'app';
    public const PAYLOAD_KEY_USER = 'user';

    public function __construct(
        protected readonly string $value
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function check(SslKey $sslKey, Application $application): bool
    {
        try {
            $payload = (new Jwt($this->value))->decode($sslKey->public);

            $id = $payload[ self::PAYLOAD_KEY_APP ]->id ?? null;

            if (!$id) {
                return false;
            }

            if (!$application->isHisId($id)) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getPayload(SslKey $sslKey): ?array
    {
        try {
            $payLoad = (new Jwt($this->value))->decode($sslKey->public);

            return $payLoad;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function generate(
        SslKey $sslKey,
        AuthMethod $authMethod,
        Application $application,
        ?User $user,
        int $lifetime
    ): self {
        $expire = (new Expire(time() + $lifetime))->getUtc();

        $payload = [
            self::PAYLOAD_KEY_EXPIRE => (string) $expire,
            self::PAYLOAD_KEY_AUTH_METHOD => (string) $authMethod->value,
            self::PAYLOAD_KEY_APP => SerializeApplication::serializeForPayload($application),
        ];

        if ($user) {
            $payload[ self::PAYLOAD_KEY_USER ] = SerializeUser::serialize($user);
        }

        return new self((string)Jwt::encode($sslKey->private, $payload, $lifetime));
    }
}
