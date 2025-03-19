<?php

declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\Entity\Application;
use Phant\Auth\Domain\Entity\Application\{
    ApiKey,
    Name,
    Id,
    Logo,
};
use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;

final class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    protected Application $fixture;

    public function setUp(): void
    {
        $this->fixture = FixtureApplication::get();
    }

    public function testConstruct(): void
    {
        $entity = new Application(
            Id::generate(),
            new Name('Foo bar'),
            new Logo('https://domain.ext/file.ext'),
            ApiKey::generate()
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);
    }

    public function testIsHisApiKey(): void
    {
        $result = $this->fixture->isHisApiKey(
            (string) $this->fixture->apiKey
        );

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testCheckInvalid(): void
    {
        $result = $this->fixture->isHisApiKey(
            ApiKey::generate()
        );

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);
    }

    public function testIsHisId(): void
    {
        $result = $this->fixture->isHisId(
            (string) $this->fixture->id
        );

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testIsHisIdInvalid(): void
    {
        $result = $this->fixture->isHisId(
            Id::generate()
        );

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);
    }

    public function testMake(): void
    {
        $entity = Application::make(
            null,
            'Foo bar',
            'https://domain.ext/file.ext',
            (string) ApiKey::generate()
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);

        $entity = Application::make(
            (string) Id::generate(),
            'Foo bar',
            'https://domain.ext/file.ext',
            (string) ApiKey::generate()
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Application::class, $entity);
    }
}
