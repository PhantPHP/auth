<?php
declare(strict_types=1);

namespace Test\Domain\Service;
use Phant\Auth\Domain\DataStructure\AccessToken;
use Phant\Auth\Domain\Service\RequestAccessFromApiKey as ServiceRequestAccessFromApiKey;

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\Service\RequestAccessFromApiKey as FixtureServiceRequestAccessFromApiKey;

final class RequestAccessFromApiKeyTest extends \PHPUnit\Framework\TestCase
{
	protected ServiceRequestAccessFromApiKey $service;
	
	public function setUp(): void
	{
		$this->service = (new FixtureServiceRequestAccessFromApiKey())();
	}
	
	public function testGetAccessToken(): void
	{
		$entity = $this->service->getAccessToken(
			FixtureApplication::get()->apiKey
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
	/*
	public function testGetAccessTokenInvalid(): void
	{
		$entity = $this->service->getAccessToken(
			$this->fixture,
			'000000'
		);
		
		$this->assertNull($entity);
	}
	*/
}
