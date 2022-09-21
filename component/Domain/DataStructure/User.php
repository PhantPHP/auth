<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\Value\{
	UserEmailAddress,
	UserFirstname,
	Lastname,
	UserRole,
};

final class User extends \Phant\DataStructure\Abstract\Entity
{
	public ?UserEmailAddress $emailAddress;
	public ?Lastname $lastname;
	public ?UserFirstname $firstname;
	public ?UserRole $role;
	
	public function __construct(
		null|string|UserEmailAddress $emailAddress,
		null|string|Lastname $lastname,
		null|string|UserFirstname $firstname,
		null|UserRole $role = null
	)
	{
		if (is_string($emailAddress)) $emailAddress = new UserEmailAddress($emailAddress);
		if (is_string($lastname)) $lastname = new Lastname($lastname);
		if (is_string($firstname)) $firstname = new UserFirstname($firstname);
		
		$this->emailAddress = $emailAddress;
		$this->lastname = $lastname;
		$this->firstname = $firstname;
		$this->role = $role;
	}
}
