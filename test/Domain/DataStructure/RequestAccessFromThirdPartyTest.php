<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	CallbackUrl,
	Id,
	State,
};

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	RequestAccessFromThirdParty as FixtureRequestAccessFromThirdParty,
};

final class RequestAccessFromThirdPartyTest extends \PHPUnit\Framework\TestCase
{
	protected RequestAccessFromThirdParty $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureRequestAccessFromThirdParty::get();
	}
	
	public function testConstruct(): void
	{
		$entity = new RequestAccessFromThirdParty(
			FixtureApplication::get(),
			new CallbackUrl('https://domain.ext/path'),
			900
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromThirdParty::class, $entity);
	}
	
	public function testGetCallbackUrl(): void
	{
		$value = $this->fixture->getCallbackUrl();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(CallbackUrl::class, $value);
	}
}
