<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\AccessToken as EntityAccessToken;

final class AccessToken
{
	public static function serialize(EntityAccessToken $accessToken): array
	{
		return [
			'token'	=> (string) $accessToken->getValue(),
			'expire' => (string) $accessToken->getExpire()->getUtc(),
		];
	}
}
