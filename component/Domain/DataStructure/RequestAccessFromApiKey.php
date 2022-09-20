<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Application;
use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	AuthMethod,
	IdRequestAccess,
	RequestAccessState,
};

use Phant\Error\NotCompliant;

final class RequestAccessFromApiKey extends \Phant\Auth\Domain\DataStructure\RequestAccess
{
	protected ApiKey $apiKey;
	
	public function __construct(
		ApiKey $apiKey,
		int $lifetime
	)
	{
		parent::__construct(
			IdRequestAccess::generate(),
			null,
			null,
			new AuthMethod(AuthMethod::API_KEY),
			new RequestAccessState(RequestAccessState::REQUESTED),
			$lifetime
		);
		
		$this->apiKey = $apiKey;
	}
}
