<?php

declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\Entity\Application;
use Phant\Auth\Domain\Service\Application as ServiceApplication;
use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\Service\Application as FixtureServiceApplication;

final class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceApplication $fixture;

    public function setUp(): void
    {
        $this->fixture = (new FixtureServiceApplication())();
    }

    public function testAdd(): void
    {
        $entity = $this->fixture->add(
            'Foo bar',
            'https://domain.ext/file.ext'
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);
    }

    public function testSet(): void
    {
        $this->fixture->set(
            FixtureApplication::get()
        );

        $this->addToAssertionCount(1);
    }

    public function testGet(): void
    {
        $entity = $this->fixture->get(
            (string) FixtureApplication::get()->id
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);
    }

    public function testGetFromApiKey(): void
    {
        $entity = $this->fixture->getFromApiKey(
            (string) FixtureApplication::get()->apiKey
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);
    }
}
