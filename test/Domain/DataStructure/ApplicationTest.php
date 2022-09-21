<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Application;
use Phant\Auth\Domain\DataStructure\Value\{
	ApiKey,
	ApplicationName,
	ApplicationId,
	ApplicationLogo,
};

use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class ApplicationTest extends \PHPUnit\Framework\TestCase
{
	protected Application $fixture;
	
	public function setUp(): void
	{
		$this->fixture = FixtureApplication::get();
	}
	
	public function testConstruct(): void
	{
		$entity = new Application(
			ApplicationId::generate(),
			new ApplicationName('Foo bar'),
			new ApplicationLogo('https://domain.ext/file.ext'),
			ApiKey::generate()
		);
		
		$this->assertIsObject($entity);
		$this->assertInstanceOf(Application::class, $entity);
	}
	
	public function testCheck(): void
	{
		$result = $this->fixture->isHisApiKey(
			$this->fixture->apiKey
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testCheckInvalid(): void
	{
		$result = $this->fixture->isHisApiKey(
			ApiKey::generate()
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
	
	public function testIsHisId(): void
	{
		$result = $this->fixture->isHisId(
			$this->fixture->id
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
	}
	
	public function testIsHisIdInvalid(): void
	{
		$result = $this->fixture->isHisId(
			ApplicationId::generate()
		);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
}
