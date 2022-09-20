<?php
declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	RequestAccessFromThirdParty,
};
use Phant\Auth\Domain\DataStructure\Value\{
	RequestAccessToken,
};
use Phant\Auth\Domain\Service\RequestAccessFromThirdParty as ServiceRequestAccessFromThirdParty;

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	RequestAccessFromThirdParty as FixtureRequestAccessFromThirdParty,
	User as FixtureUser,
};
use Phant\Auth\Fixture\Service\{
	RequestAccessFromThirdParty as FixtureServiceRequestAccessFromThirdParty,
};

use Phant\Error\NotCompliant;

final class RequestAccessFromThirdPartyTest extends \PHPUnit\Framework\TestCase
{
	protected ServiceRequestAccessFromThirdParty $service;
	protected RequestAccessToken $fixture;
	
	public function setUp(): void
	{
		$this->service = (new FixtureServiceRequestAccessFromThirdParty())();
		$this->fixture = $this->service->generate(
			FixtureApplication::get()
		);
	}
	
	public function testGenerate(): void
	{
		$this->assertIsObject($this->fixture);
		$this->assertInstanceOf(RequestAccessToken::class, $this->fixture);
	}
	
	public function testVerify(): void
	{
		$this->service->verify(
			$this->fixture,
			FixtureUser::get(),
			true
		);
		
		$this->addToAssertionCount(1);
	}
	
	public function testGetAccessToken(): void
	{
		$this->service->verify(
			$this->fixture,
			FixtureUser::get(),
			true
		);
		
		$entity = $this->service->getAccessToken(
			$this->fixture
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
	
	public function testGetAccessTokenInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$this->service->verify(
			$this->fixture,
			FixtureUser::get(),
			false
		);
		
		$entity = $this->service->getAccessToken(
			$this->fixture
		);
	}
}
