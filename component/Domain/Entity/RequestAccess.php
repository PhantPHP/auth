<?php

declare(strict_types=1);

namespace Phant\Auth\Domain\Entity;

use Phant\Auth\Domain\Entity\{
    Application,
    SslKey,
    User,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    AuthMethod,
    Id,
    State,
    Token,
};
use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

abstract class RequestAccess
{
    public const TOKEN_PAYLOAD_LIFETIME = 'lifetime';
    public const TOKEN_PAYLOAD_EXPIRATION = 'expiration';
    public const TOKEN_PAYLOAD_ID = 'request_access_id';

    public readonly int $expiration;

    public function __construct(
        public readonly Id $id,
        public ?Application $application,
        public ?User $user,
        public readonly AuthMethod $authMethod,
        public State $state,
        public readonly int $lifetime
    ) {
        $this->expiration = time() + $lifetime;
    }

    public function setApplication(Application $application): void
    {
        if ($this->application) {
            throw new NotAuthorized('It is not allowed to modify the application');
        }

        $this->application = $application;
    }

    public function setUser(User $user): void
    {
        if ($this->user) {
            throw new NotAuthorized('It is not allowed to modify the user');
        }

        $this->user = $user;
    }

    public function canBeSetStateTo(State $state): bool
    {
        return $this->state->canBeSetTo($state);
    }

    public function setState(State $state): self
    {
        if (!$this->state->canBeSetTo($state)) {
            throw new NotAuthorized('State can be set to set to : ' . $state->value);
        }

        $this->state = $state;

        return $this;
    }

    public function tokenizeId(SslKey $sslKey): Token
    {
        $id = (string)$this->id;

        $datas = json_encode([
            self::TOKEN_PAYLOAD_LIFETIME => $this->lifetime,
            self::TOKEN_PAYLOAD_EXPIRATION => $this->expiration,
            self::TOKEN_PAYLOAD_ID => $id,
        ]);

        $token = $sslKey->encrypt($datas);

        $token = strtr(base64_encode($token), '+/=', '._-');

        return new Token($token);
    }

    public static function untokenizeId(Token $token, SslKey $sslKey): Id
    {
        $token = base64_decode(strtr((string)$token, '._-', '+/='));

        $datas = $sslKey->decrypt($token);

        $datas = json_decode($datas, true);

        $expiration = $datas[ self::TOKEN_PAYLOAD_EXPIRATION ] ?? 0;
        if ($expiration < time()) {
            throw new NotCompliant('Token expired');
        }

        $id = $datas[ self::TOKEN_PAYLOAD_ID ];

        return new Id($id);
    }
}
