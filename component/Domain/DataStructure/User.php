<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Value\{
	EmailAddress,
	Firstname,
	Lastname,
	Role,
};

final class User extends \Phant\DataStructure\Abstract\Entity
{
	public ?EmailAddress $emailAddress;
	public ?Lastname $lastname;
	public ?Firstname $firstname;
	public ?Role $role;
	
	public function __construct(
		null|string|EmailAddress $emailAddress,
		null|string|Lastname $lastname,
		null|string|Firstname $firstname,
		null|Role $role = null
	)
	{
		if (is_string($emailAddress)) $emailAddress = new EmailAddress($emailAddress);
		if (is_string($lastname)) $lastname = new Lastname($lastname);
		if (is_string($firstname)) $firstname = new Firstname($firstname);
		
		$this->emailAddress = $emailAddress;
		$this->lastname = $lastname;
		$this->firstname = $firstname;
		$this->role = $role;
	}
}
