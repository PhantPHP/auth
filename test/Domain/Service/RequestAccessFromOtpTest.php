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

final class RequestAccessFromOtpTest extends \PHPUnit\Framework\TestCase
{
	protected ServiceRequestAccessFromOtp $service;
	protected RequestAccessToken $fixture;
	protected SimpleCache $cache;
	
	public function setUp(): void
	{
		$this->service = (new FixtureServiceRequestAccessFromOtp())();
		$this->fixture = $this->service->generate(
			FixtureUser::get(),
			FixtureApplication::get()
		);
		$this->cache = new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'user-notification');
	}
	
	public function testGenerate(): void
	{
		$this->assertIsObject($this->fixture);
		$this->assertInstanceOf(RequestAccessToken::class, $this->fixture);
	}
	
	public function testGetAccessToken(): void
	{
		$otp = $this->cache->get((string)$this->fixture);
		
		$entity = $this->service->getAccessToken(
			$this->fixture,
			$otp
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
	
	public function testGetAccessTokenInvalid(): void
	{
		$entity = $this->service->getAccessToken(
			$this->fixture,
			'000000'
		);
		
		$this->assertNull($entity);
	}
}