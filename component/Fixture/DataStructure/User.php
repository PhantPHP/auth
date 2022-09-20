<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\User as EntityUser;
use Phant\Auth\Domain\DataStructure\Value\{
	EmailAddress,
	Firstname,
	Lastname,
	Role,
};

final class User
{
	public static function get(): EntityUser
	{
		return new EntityUser(
			new EmailAddress('john.doe@domain.ext'),
			new Lastname('DOE'),
			new Firstname('John'),
			null
		);
	}
}
