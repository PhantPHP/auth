<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\RequestAccess;

use Phant\Auth\Domain\DataStructure\RequestAccess\State;

final class StateTest extends \PHPUnit\Framework\TestCase
{
	public function testCanBeSetTo(): void
	{
		// Requested to ...
		$result = (new State(State::REQUESTED))
			->canBeSetTo(State::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::REQUESTED))
			->canBeSetTo(State::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		$result = (new State(State::REQUESTED))
			->canBeSetTo(State::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		$result = (new State(State::REQUESTED))
			->canBeSetTo(State::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		// Refused to ...
		$result = (new State(State::REFUSED))
			->canBeSetTo(State::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::REFUSED))
			->canBeSetTo(State::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::REFUSED))
			->canBeSetTo(State::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::REFUSED))
			->canBeSetTo(State::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		// Verified to ...
		$result = (new State(State::VERIFIED))
			->canBeSetTo(State::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::VERIFIED))
			->canBeSetTo(State::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::VERIFIED))
			->canBeSetTo(State::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::VERIFIED))
			->canBeSetTo(State::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(true, $result);
		
		
		// Granted to ...
		$result = (new State(State::GRANTED))
			->canBeSetTo(State::REQUESTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::GRANTED))
			->canBeSetTo(State::REFUSED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::GRANTED))
			->canBeSetTo(State::VERIFIED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
		
		
		$result = (new State(State::GRANTED))
			->canBeSetTo(State::GRANTED);
		
		$this->assertIsBool($result);
		$this->assertEquals(false, $result);
	}
}
