<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\AccessToken;

use Phant\Auth\Fixture\DataStructure\{
	AccessToken as FixtureAccessToken,
	Application as FixtureApplication,
	User as FixtureUser,
};
use Phant\Auth\Fixture\DataStructure\Value\{
	SslKey as FixtureSslKey,
};

final class AccessTokenTest extends \PHPUnit\Framework\TestCase
{
	protected AccessToken $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureAccessToken::get();
	}
	
	public function testConstruct(): void
	{
		$entity = new AccessToken($this->fixture->getValue());
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
	
	public function testCheck(): void
	{
		$result = $this->fixture->check(
			FixtureSslKey::get(),
			FixtureApplication::get()
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testCheckInvalid(): void
	{
		$result = $this->fixture->check(
			FixtureSslKey::getInvalid(),
			FixtureApplication::get()
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
	
	public function testGetPayload(): void
	{
		$result = $this->fixture->getPayload(
			FixtureSslKey::get()
		);
		
		$this->assertIsArray($result);
		$this->assertArrayHasKey(AccessToken::PAYLOAD_KEY_APP, $result);
		$this->assertArrayHasKey(AccessToken::PAYLOAD_KEY_USER, $result);
	}
	
	public function testGetPayloadInvalid(): void
	{
		$result = $this->fixture->getPayload(
			FixtureSslKey::getInvalid()
		);
		
		$this->assertNull($result);
	}
	
	public function testGenerate(): void
	{
		$entity = AccessToken::generate(
			FixtureSslKey::get(),
			FixtureApplication::get(),
			FixtureUser::get()
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(AccessToken::class, $entity);
	}
}
