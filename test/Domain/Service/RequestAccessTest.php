<?php
declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\DataStructure\RequestAccessFromOtp;
use Phant\Auth\Domain\DataStructure\Value\RequestAccessToken;
use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;

use Phant\Auth\Fixture\DataStructure\RequestAccessFromOtp as FixtureRequestAccessFromOtp;
use Phant\Auth\Fixture\Service\RequestAccess as FixtureServiceRequestAccess;

final class RequestAccessTest extends \PHPUnit\Framework\TestCase
{
	protected ServiceRequestAccess $service;
	protected RequestAccessFromOtp $fixture;
	
	public function setUp(): void
	{
		$this->service = (new FixtureServiceRequestAccess())();
		$this->fixture = FixtureRequestAccessFromOtp::get();
	}
	
	public function testSet(): void
	{
		$this->service->set(
			$this->fixture
		);
		
		$this->addToAssertionCount(1);
	}
	
	public function testGet(): void
	{
		$entity = $this->service->get(
			$this->fixture->getId()
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
	}
	
	public function testGetToken(): void
	{
		$value = $this->service->getToken(
			$this->fixture
		);
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(RequestAccessToken::class, $value);
	}
	
	public function testGetFromToken(): void
	{
		$entity = $this->service->getFromToken(
			$this->service->getToken(
				$this->fixture
			)
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
	}
}
