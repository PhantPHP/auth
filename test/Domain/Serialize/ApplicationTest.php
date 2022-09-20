<?php
declare(strict_types=1);

namespace Test\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\Application;
use Phant\Auth\Domain\Serialize\Application as SerializeApplication;

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class ApplicationTest extends \PHPUnit\Framework\TestCase
{
	protected Application $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureApplication::get();
	}
	
	public function testSerialize(): void
	{
		$value = SerializeApplication::serialize(
			$this->fixture
		);
		
		$this->assertIsArray($value);
		$this->assertCount(4, $value);
		$this->assertArrayHasKey('id', $value);
		$this->assertArrayHasKey('name', $value);
		$this->assertArrayHasKey('logo', $value);
		$this->assertArrayHasKey('api_key', $value);
	}
}
