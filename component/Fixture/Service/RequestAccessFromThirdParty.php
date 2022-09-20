<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\Service;

use Phant\Auth\Domain\Service\RequestAccessFromThirdParty as ServiceRequestAccessFromThirdParty;

use Phant\Auth\Fixture\Service\{
	AccessToken as FixtureServiceAccessToken,
	RequestAccess as FixtureServiceRequestAccess,
};

final class RequestAccessFromThirdParty
{
	public function __invoke(): ServiceRequestAccessFromThirdParty
	{
		return new ServiceRequestAccessFromThirdParty(
			(new FixtureServiceRequestAccess())(),
			(new FixtureServiceAccessToken())()
		);
	}
}
