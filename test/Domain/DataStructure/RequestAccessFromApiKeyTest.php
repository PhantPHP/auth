<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\RequestAccessFromApiKey;
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	Otp,
	RequestAccessState,
};

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	RequestAccessFromApiKey as FixtureRequestAccessFromApiKey,
	User as FixtureUser,
};

final class RequestAccessFromApiKeyTest extends \PHPUnit\Framework\TestCase
{
	protected RequestAccessFromApiKey $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureRequestAccessFromApiKey::get();
	}
	
	public function testConstruct(): void
	{
		$entity = new RequestAccessFromApiKey(
			null,
			new RequestAccessState(RequestAccessState::REQUESTED),
			FixtureApplication::get()->apiKey
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromApiKey::class, $entity);
	}
}
