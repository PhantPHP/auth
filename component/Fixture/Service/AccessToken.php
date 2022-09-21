<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\AccessToken as ServiceAccessToken;

use Phant\Auth\Fixture\DataStructure\SslKey as FixtureSslKey;
use Phant\Auth\Fixture\Service\RequestAccess as FixtureServiceRequestAccess;

final class AccessToken
{
	public function __invoke(): ServiceAccessToken
	{
		return new ServiceAccessToken(
			FixtureSslKey::get(),
			(new FixtureServiceRequestAccess())()
		);
	}
}
