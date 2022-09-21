<?php
declare(strict_types=1);

namespace Phant\Auth\Fixture\DataStructure;

use Phant\Auth\Domain\DataStructure\User as EntityUser;
use Phant\Auth\Domain\DataStructure\Value\{
	UserEmailAddress,
	Firstname,
	Lastname,
	UserRole,
};

final class User
{
	public static function get(): EntityUser
	{
		return new EntityUser(
			new UserEmailAddress('john.doe@domain.ext'),
			new Lastname('DOE'),
			new Firstname('John'),
			null
		);
	}
}
