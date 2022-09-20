<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\Value;

use Phant\Auth\Domain\DataStructure\Value\ApiKey;

final class ApiKeyTest extends \PHPUnit\Framework\TestCase
{
	public function testGenerate(): void
	{
		$value = ApiKey::generate();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(ApiKey::class, $value);
	}
}
