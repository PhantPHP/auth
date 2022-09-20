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
		IdRequestAccess $id,
		RequestAccessState $state,
		ApiKey $apiKey,
		int $lifetime = self::LIFETIME
	)
	{
		parent::__construct(
			$id,
			null,
			new AuthMethod(AuthMethod::API_KEY),
			$state,
			null,
			$lifetime
		);
		
		$this->apiKey = $apiKey;
	}
}
