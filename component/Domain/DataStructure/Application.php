<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	ApplicationName,
	IdApplication,
	Logo,
};

final class Application extends \Phant\DataStructure\Abstract\Entity
{
	public IdApplication $id;
	public ApplicationName $name;
	public ?Logo $logo;
	public ApiKey $apiKey;
	
	public function __construct(
		IdApplication $id,
		ApplicationName $name,
		?Logo $logo,
		ApiKey $apiKey
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->logo = $logo;
		$this->apiKey = $apiKey;
	}
	
	public function isHisApiKey(ApiKey $apiKey): bool
	{
		return ((string)$this->apiKey === (string)$apiKey);
	}
	
	public function isHisId(IdApplication $id): bool
	{
		return ((string)$this->id === (string)$id);
	}
}
