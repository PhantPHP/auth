<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	User,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	AuthMethod,
	CallbackUrl,
	Id,
	State,
};

final class RequestAccessFromThirdParty extends \Phant\Auth\Domain\DataStructure\RequestAccess
{
	protected CallbackUrl $callbackUrl;
	
	public function __construct(
		Application $application,
		CallbackUrl $callbackUrl,
		int $lifetime
	)
	{
		parent::__construct(
			Id::generate(),
			$application,
			null,
			new AuthMethod(AuthMethod::THIRD_PARTY),
			new State(State::REQUESTED),
			$lifetime
		);
		
		$this->callbackUrl = $callbackUrl;
	}
	
	public function getCallbackUrl(): CallbackUrl
	{
		return $this->callbackUrl;
	}
}
