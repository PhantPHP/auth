<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\Value;

final class RequestAccessState extends \Phant\DataStructure\Abstract\Enum
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
			case RequestAccessState::REQUESTED :
				
				break;
				
			case RequestAccessState::REFUSED :
				
				return ($this->value == RequestAccessState::REQUESTED);
				
			case RequestAccessState::VERIFIED :
				
				return ($this->value == RequestAccessState::REQUESTED);
				
			case RequestAccessState::GRANTED :
				
				return ($this->value == RequestAccessState::VERIFIED);
				
		}
		
		return false;
	}
}
