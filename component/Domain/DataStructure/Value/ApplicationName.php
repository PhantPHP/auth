<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure\Value;

final class ApplicationName extends \Phant\DataStructure\Abstract\Value\Varchar
{
	const PATTERN = '/^.{0,250}$/';
	
	public function __construct(string $name)
	{
		$name = trim($name);
		
		parent::__construct($name);
	}
}
