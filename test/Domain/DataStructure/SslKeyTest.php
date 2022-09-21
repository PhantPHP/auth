<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\SslKey;
use Phant\Auth\Fixture\DataStructure\SslKey as FixtureSslKey;

use Phant\Error\NotCompliant;

final class SslKeyTest extends \PHPUnit\Framework\TestCase
{
	protected SslKey $fixture;
	protected SslKey $fixtureInvalid;
	
	public function setUp(): void
	{
		$this->fixture = FixtureSslKey::get();
		$this->fixtureInvalid = FixtureSslKey::getInvalid();
	}
	
	public function testGetPrivate(): void
	{
		$result = $this->fixture->getPrivate();
		
		$this->assertIsString($result);
	}
	
	public function testGetPublic(): void
	{
		$result = $this->fixture->getPublic();
		
		$this->assertIsString($result);
	}
	
	public function testEncrypt(): void
	{
		$result = $this->fixture->encrypt('Foo bar');
		
		$this->assertIsString($result);
	}
	
	public function testEncryptInvalid(): void
	{
		$this->expectException(NotCompliant::class);
		
		$result = $this->fixtureInvalid->encrypt('Foo bar');
	}
	
	public function testEncryptInvalidBis(): void
	{
		$this->expectException(NotCompliant::class);
		
		$result = $this->fixtureInvalid->encrypt('');
	}
	
	public function testDecrypt(): void
	{
		$result = $this->fixture->decrypt(
			$this->fixture->encrypt('Foo bar')
		);
		
		$this->assertIsString($result);
	}
	
	public function testDecryptInvalid(): void
	{
		$this->expectException(NotCompliant::class);
			
		$result = $this->fixtureInvalid->decrypt(
			$this->fixture->encrypt('Foo bar')
		);
	}
}
