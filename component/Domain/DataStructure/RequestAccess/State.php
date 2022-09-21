<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\RequestAccess;

final class State extends \Phant\DataStructure\Abstract\Enum
{
	public const REQUESTED = 'requested';
	public const REFUSED = 'refused';
	public const VERIFIED = 'verified';
	public const GRANTED = 'granted';
	
	public const VALUES = [
		self::REQUESTED => 'Requested',
		self::REFUSED => 'Refused',
		self::VERIFIED => 'Verified',
		self::GRANTED => 'Granted',
	];
	
	public function canBeSetTo(string|self $state): bool
	{
		if (is_string($state)) $state = new self($state);
		
		switch ($state->getValue()) {
			case State::REQUESTED :
				
				break;
				
			case State::REFUSED :
				
				return ($this->value == State::REQUESTED);
				
			case State::VERIFIED :
				
				return ($this->value == State::REQUESTED);
				
			case State::GRANTED :
				
				return ($this->value == State::VERIFIED);
				
		}
		
		return false;
	}
}
