<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\Value;

final class ApiKey extends \Phant\DataStructure\Abstract\Value\Varchar
{
	public const PATTERN = '/^[0-9a-zA-Z]{8}\.[0-9a-zA-Z]{64}$/';
	
	public static function generate(): ApiKey
	{
		return new ApiKey(self::generateRandomString(8) . '.' . self::generateRandomString(64));
	}
	
	private static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		
		return $randomString;
	}
}
