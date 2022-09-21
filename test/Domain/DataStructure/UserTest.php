<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\User;
use Phant\Auth\Domain\DataStructure\User\{
	EmailAddress,
	Firstname,
	Lastname,
	Role,
};

use Phant\Auth\Fixture\DataStructure\User as FixtureUser;

final class UserTest extends \PHPUnit\Framework\TestCase
{
	protected User $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureUser::get();
	}
	
	public function testConstruct(): void
	{
		$entity = new User(
			'john.doe@domain.ext',
			'John',
			'DOE'
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(User::class, $entity);
	}
}
