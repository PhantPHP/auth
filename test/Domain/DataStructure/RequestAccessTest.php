<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	RequestAccessFromOtp,
	User,
};
use Phant\Auth\Domain\DataStructure\Value\{
	AuthMethod,
	IdRequestAccess,
	Otp,
	RequestAccessState,
	RequestAccessToken,
};

use Phant\Auth\Fixture\DataStructure\{
	RequestAccessFromOtp as FixtureRequestAccessFromOtp,
	User as FixtureUser,
};
use Phant\Auth\Fixture\DataStructure\Value\{
	SslKey as FixtureSslKey,
};

use Phant\Error\NotCompliant;

final class RequestAccessTest extends \PHPUnit\Framework\TestCase
{
	protected RequestAccessFromOtp $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureRequestAccessFromOtp::get();
	}
	
	public function testGetId(): void
	{
		$value = $this->fixture->getId();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(IdRequestAccess::class, $value);
	}
	
	public function testApplication(): void
	{
		$value = $this->fixture->getApplication();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(Application::class, $value);
	}
	
	public function testGetAuthMethod(): void
	{
		$value = $this->fixture->getAuthMethod();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(AuthMethod::class, $value);
	}
	
	public function testGetState(): void
	{
		$value = $this->fixture->getState();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(RequestAccessState::class, $value);
	}
	
	public function testGetUser(): void
	{
		$value = $this->fixture->getUser();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(User::class, $value);
	}
	
	public function testSetUser(): void
	{
		$this->fixture->setUser(FixtureUser::get());
		
		$value = $this->fixture->getUser();
		
		$this->assertIsObject($value);
		$this->assertInstanceOf(User::class, $value);
		$this->assertEquals(FixtureUser::get(), $value);
	}
	
	public function testCanBeSetStateTo(): void
	{
		$result = $this->fixture->canBeSetStateTo(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testSetState(): void
	{
		$entity = $this->fixture->setState(new RequestAccessState(RequestAccessState::VERIFIED));
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
		
		$this->assertIsObject($entity->getState());
		$this->assertInstanceOf(RequestAccessState::class, $entity->getState());
		$this->assertEquals(new RequestAccessState(RequestAccessState::VERIFIED), $entity->getState());
	}
	
	public function testSetStateInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$entity = $this->fixture->setState(new RequestAccessState(RequestAccessState::REQUESTED));
	}
	
	public function testTokenizeIdAndUntokenizeId(): void
	{
		$result = $this->fixture->tokenizeId(FixtureSslKey::get());
		
		$this->assertIsObject($result);
		$this->assertInstanceOf(RequestAccessToken::class, $result);
		
		$entity = $this->fixture->untokenizeId($result, FixtureSslKey::get());
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(IdRequestAccess::class, $entity);
	}
	
	public function testTokenizeIdInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$this->fixture->tokenizeId(FixtureSslKey::getInvalid());
	}
	
	public function testUntokenizeIdInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$this->fixture->untokenizeId(
			$this->fixture->tokenizeId(FixtureSslKey::get()),
			FixtureSslKey::getInvalid()
		);
	}
	
	public function testUntokenizeIdExpired(): void
	{
		$this->expectException(NotCompliant::class);
		
		$this->fixture->untokenizeId(
			FixtureRequestAccessFromOtp::getExpired()->tokenizeId(FixtureSslKey::get()),
			FixtureSslKey::get()
		);
	}
}
