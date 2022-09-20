<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\Application as EntityApplication;

final class Application
{
	public static function serialize(EntityApplication $application): array
	{
		return [
			'id'	=> (string) $application->id,
			'name'	=> (string) $application->name,
			'logo'	=> $application->logo ? (string) $application->logo : null,
			'api_key'	=> (string) $application->apiKey,
		];
	}
}
