<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Serialize;

use Phant\Auth\Domain\DataStructure\User as EntityUser;

final class User
{
	public static function serialize(EntityUser $user): array
	{
		return [
			'email_address'	=> (string)$user->emailAddress,
			'lastname'		=> $user->lastname ? (string)$user->lastname : null,
			'firstname'		=> $user->firstname ? (string)$user->firstname : null,
			'role'			=> $user->role ? (string)$user->role : null,
		];
	}
}
