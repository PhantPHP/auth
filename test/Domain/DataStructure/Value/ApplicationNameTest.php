<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\Value;

use Phant\Auth\Domain\DataStructure\Value\ApplicationName;

use Phant\Error\NotCompliant;

final class ApplicationNameTest extends \PHPUnit\Framework\TestCase
{
	public function testConstruct(): void
	{
		$result = new ApplicationName('Foo bar');
		
		$this->assertIsObject($result);
		$this->assertInstanceOf(ApplicationName::class, $result);
	}
	
	public function testConstructFail(): void
	{
		$this->expectException(NotCompliant::class);
		
		new ApplicationName('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
	}
}
