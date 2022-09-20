<?php
declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\DataStructure\{
	AccessToken,
	RequestAccessFromOtp,
};
use Phant\Auth\Domain\DataStructure\Value\{
	RequestAccessToken,
};
use Phant\Auth\Domain\Service\RequestAccessFromOtp as ServiceRequestAccessFromOtp;

use Phant\Auth\Fixture\DataStructure\{
	Application as FixtureApplication,
	User as FixtureUser,
	RequestAccessFromOtp as FixtureRequestAccessFromOtp,
};
use Phant\Auth\Fixture\Port\UserNotification as FixturePortUserNotification;
use Phant\Auth\Fixture\Service\{
	RequestAccessFromOtp as FixtureServiceRequestAccessFromOtp,
};
use Phant\Cache\SimpleCache;

use Phant\Error\NotCompliant;

final class RequestAccessFromOtpTest extends \PHPUnit\Framework\TestCase
{
	protected ServiceRequestAccessFromOtp $service;
	protected RequestAccessToken $fixture;
	protected SimpleCache $cache;
	
	public function setUp(): void
	{
		$this->service = (new FixtureServiceRequestAccessFromOtp())();
		$this->fixture = $this->service->generate(
			FixtureApplication::get(),
			FixtureUser::get()
		);
		$this->cache = new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'user-notification');
	}
	
	public function testGenerate(): void
	{
		$this->assertIsObject($this->fixture);
		$this->assertInstanceOf(RequestAccessToken::class, $this->fixture);
	}
	
	public function testGenerateInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$this->service->generate(
			FixtureApplication::get(),
			FixtureUser::get(),
			0
		);
	}
	
	public function testVerify(): void
	{
		$otp = $this->cache->get((string)$this->fixture);
		
		$result = $this->service->verify(
			$this->fixture,
			$otp
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testVerifyInvalid(): void
	{
		$result = $this->service->verify(
			$this->fixture,
			'000000'
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
	
	public function testGetNumberOfRemainingAttempts(): void
	{
		$result = $this->service->getNumberOfRemainingAttempts(
			$this->fixture
		);
		
		$this->assertIsInt($result);
		$this->assertEquals(3, $result);
	}
	
	public function testGetAccessToken(): void
	{
		$otp = $this->cache->get((string)$this->fixture);
		
		$result = $this->service->verify(
			$this->fixture,
			$otp
		);
		
		$entity = $this->service->getAccessToken(
			$this->fixture,
			$otp
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
}
