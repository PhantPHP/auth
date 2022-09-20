<?php
declare(strict_types=1);

namespace Test\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\User;
use Phant\Auth\Domain\Serialize\User as SerializeUser;

use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class UserTest extends \PHPUnit\Framework\TestCase
{
	protected User $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureUser::get();
	}
	
	public function testSerialize(): void
	{
		$value = SerializeUser::serialize(
			$this->fixture
		);
		
		$this->assertIsArray($value);
		$this->assertCount(4, $value);
		$this->assertArrayHasKey('email_address', $value);
		$this->assertArrayHasKey('lastname', $value);
		$this->assertArrayHasKey('firstname', $value);
		$this->assertArrayHasKey('role', $value);
	}
}
