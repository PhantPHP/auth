<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
	IdRequestAccess,
	RequestAccessState,
};

final class RequestAccessFromThirdParty extends \Phant\Auth\Domain\DataStructure\RequestAccess
{
	public function __construct(
		Application $application,
		int $lifetime
	)
	{
		parent::__construct(
			IdRequestAccess::generate(),
			$application,
			null,
			new AuthMethod(AuthMethod::THIRD_PARTY),
			new RequestAccessState(RequestAccessState::REQUESTED),
			$lifetime
		);
	}
}
