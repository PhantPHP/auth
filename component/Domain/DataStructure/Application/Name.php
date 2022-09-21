<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\Application;

final class Name extends \Phant\DataStructure\Abstract\Value\Varchar
{
	const PATTERN = '/^.{0,250}$/';
	
	public function __construct(string $name)
	{
		$name = trim($name);
		
		parent::__construct($name);
	}
}
