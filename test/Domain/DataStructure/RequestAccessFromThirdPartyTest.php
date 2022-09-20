<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	RequestAccessState,
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
			IdRequestAccess::generate(),
			FixtureApplication::get(),
			new RequestAccessState(RequestAccessState::REQUESTED),
			null
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromThirdParty::class, $entity);
	}
}
