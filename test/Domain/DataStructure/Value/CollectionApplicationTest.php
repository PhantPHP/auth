<?php
declare(strict_types=1);

namespace Test\Domain\DataStructure\Value;

use Phant\Auth\Domain\DataStructure\Value\CollectionApplication;
use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class CollectionApplicationTest extends \PHPUnit\Framework\TestCase
{
	protected CollectionApplication $fixture;
	
	public function testAddApplication(): void
	{
		$collection = new CollectionApplication();
		
		$this->assertEquals(0, $collection->getNbItems());
		
		$collection->addApplication(
			FixtureApplication::get()
		);
		
		$this->assertEquals(1, $collection->getNbItems());
	}
	
	public function testSearchById(): void
	{
		$collection = FixtureApplication::getCollection();
		
		$result = $collection->searchById(
			FixtureApplication::get()->id
		);
		
		$this->assertIsObject($result);
		
		$collection = FixtureApplication::getCollection();
		
		$result = $collection->searchById(
			'1b3f18e5-c12d-4063-bf27-d77c2558ea1a'
		);
		
		$this->assertNull($result);
	}
	
	public function testSearchByApiKey(): void
	{
		$collection = FixtureApplication::getCollection();
		
		$result = $collection->searchByApiKey(
			FixtureApplication::get()->apiKey
		);
		
		$this->assertIsObject($result);
		
		$collection = FixtureApplication::getCollection();
		
		$result = $collection->searchByApiKey(
			'XXXXXXXX.XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
		);
		
		$this->assertNull($result);
	}
}
