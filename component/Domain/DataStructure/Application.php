<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Application\{
	ApiKey,
	Name,
	Id,
	Logo,
};

final class Application extends \Phant\DataStructure\Abstract\Entity
{
	public Id $id;
	public Name $name;
	public ?Logo $logo;
	public ApiKey $apiKey;
	
	public function __construct(
		null|string|Id $id,
		null|string|Name $name,
		null|string|Logo $logo,
		string|ApiKey $apiKey
	)
	{
		if (is_string($id)) $id = new Id($id);
		if (is_string($name)) $name = new Name($name);
		if (is_string($logo)) $logo = new Logo($logo);
		
		$this->id = $id;
		$this->name = $name;
		$this->logo = $logo;
		$this->apiKey = $apiKey;
	}
	
	public function isHisApiKey(string|ApiKey $apiKey): bool
	{
		if (is_string($apiKey)) $apiKey = new ApiKey($apiKey);
		
		return ((string)$this->apiKey === (string)$apiKey);
	}
	
	public function isHisId(string|Id $id): bool
	{
		if (is_string($id)) $id = new Id($id);
		
		return ((string)$this->id === (string)$id);
	}
}
