<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\Application as EntityApplication;
use Phant\Auth\Domain\DataStructure\Application\{
	ApiKey,
	Collection,
	Id,
	Logo,
	Name,
};

final class Application
{
	const DATAS = [
		[
			'id'		=> 'c2280258-78e9-48fd-905f-2c8e023acb9c',
			'name'		=> 'Flashpoint',
			'logo'		=> 'https://via.placeholder.com/400x200?text=Flashpoint',
			'api_key'	=> 'fw9LAIpY.rP6ogyVSQtLu9dV1pj94vXnzzEO5sHJWGxwa5c1g6Lkz06Z9tcnsmF4SbyTjyDSh',
		],
		[
			'id'		=> 'fa9a72a5-5c21-4056-818a-66b26626874a',
			'name'		=> 'LiveTube',
			'logo'		=> 'https://via.placeholder.com/400x200?text=LiveTube',
			'api_key'	=> 'kMgyGJlO.H6pYDtv8E51rK9D0gDSTYknvM7oEGgLL3Lbekj3EqWsMpDSz0Oo6ri5l7mDVnpkE',
		],
		[
			'id'		=> '1fa914ab-5d34-48d5-82fd-fc364c62b0f9',
			'name'		=> 'Taskeo',
			'logo'		=> 'https://via.placeholder.com/400x200?text=Taskeo',
			'api_key'	=> 'R2FpV3FU.w5skjmLm4VDylSrH1cSu7EQfrHVkIB5vacBF63Ni5WI8sIKAIQ2WJqISx7sl4TlJ',
		],
	];
	
	public static function get(): EntityApplication
	{
		$datas = self::DATAS[0];
		
		return self::buildFromDatas($datas);
	}
	
	public static function getCollection(): Collection
	{
		$collection = new Collection();
		
		foreach (self::DATAS as $datas) {
			$collection->addApplication(
				self::buildFromDatas($datas)
			);
		}
		
		return $collection;
	}
	
	private static function buildFromDatas(array $datas): EntityApplication
	{
		return new EntityApplication(
			new Id($datas['id']),
			new Name($datas['name']),
			new Logo($datas['logo']),
			new ApiKey($datas['api_key'])
		);
	}
}
