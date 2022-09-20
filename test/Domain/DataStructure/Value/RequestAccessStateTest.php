<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\Value;

use Phant\Auth\Domain\DataStructure\Value\RequestAccessState;

final class RequestAccessStateTest extends \PHPUnit\Framework\TestCase
{
	public function testCanBeSetTo(): void
	{
		// Requested to ...
		$result = (new RequestAccessState(RequestAccessState::REQUESTED))
			->canBeSetTo(RequestAccessState::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REQUESTED))
			->canBeSetTo(RequestAccessState::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REQUESTED))
			->canBeSetTo(RequestAccessState::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REQUESTED))
			->canBeSetTo(RequestAccessState::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		// Refused to ...
		$result = (new RequestAccessState(RequestAccessState::REFUSED))
			->canBeSetTo(RequestAccessState::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REFUSED))
			->canBeSetTo(RequestAccessState::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REFUSED))
			->canBeSetTo(RequestAccessState::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::REFUSED))
			->canBeSetTo(RequestAccessState::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		// Verified to ...
		$result = (new RequestAccessState(RequestAccessState::VERIFIED))
			->canBeSetTo(RequestAccessState::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::VERIFIED))
			->canBeSetTo(RequestAccessState::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::VERIFIED))
			->canBeSetTo(RequestAccessState::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::VERIFIED))
			->canBeSetTo(RequestAccessState::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		// Granted to ...
		$result = (new RequestAccessState(RequestAccessState::GRANTED))
			->canBeSetTo(RequestAccessState::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::GRANTED))
			->canBeSetTo(RequestAccessState::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::GRANTED))
			->canBeSetTo(RequestAccessState::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new RequestAccessState(RequestAccessState::GRANTED))
			->canBeSetTo(RequestAccessState::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
}
