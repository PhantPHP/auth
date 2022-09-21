<?php
declare(strict_types=1);

namespace Test\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\AccessToken;
use Phant\Auth\Domain\Serialize\AccessToken as SerializeAccessToken;

use Phant\Auth\Fixture\DataStructure\AccessToken as FixtureAccessToken;

final class AccessTokenTest extends \PHPUnit\Framework\TestCase
{
	protected AccessToken $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureAccessToken::get();
	}
	
	public function testSerialize(): void
	{
		$value = SerializeAccessToken::serialize(
			$this->fixture
		);
		
		$this->assertIsArray($value);
		$this->assertCount(2, $value);
		$this->assertArrayHasKey('token', $value);
		$this->assertArrayHasKey('expire', $value);
	}
}
