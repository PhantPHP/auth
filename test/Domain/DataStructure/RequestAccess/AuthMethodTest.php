<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\RequestAccess;

use Phant\Auth\Domain\DataStructure\RequestAccess\AuthMethod;

final class AuthMethodTest extends \PHPUnit\Framework\TestCase
{
	public function testIs(): void
	{
		$result = (new AuthMethod(AuthMethod::API_KEY))->is(AuthMethod::API_KEY);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testIsDifferent(): void
	{
		$result = (new AuthMethod(AuthMethod::API_KEY))->is(AuthMethod::OTP);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
}
