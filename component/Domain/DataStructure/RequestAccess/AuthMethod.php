<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\RequestAccess;

final class AuthMethod extends \Phant\DataStructure\Abstract\Enum
{
	public const API_KEY = 'api_key';
	public const OTP = 'otp';
	public const THIRD_PARTY = 'third_party';
	
	public const VALUES = [
		self::API_KEY => 'API key',
		self::OTP => 'OTP',
		self::THIRD_PARTY => 'Third party',
	];
	
	public function is(string|self $authMethod): bool
	{
		return ($this->value == (string)$authMethod);
	}
}
