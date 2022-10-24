<?php

declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\Entity\RequestAccessFromApiKey;
use Phant\Auth\Domain\Entity\RequestAccess\{
    Id,
    Otp,
    State,
};

use Phant\Auth\Fixture\DataStructure\{
    Application as FixtureApplication,
    RequestAccessFromApiKey as FixtureRequestAccessFromApiKey,
    User as FixtureUser,
};

final class RequestAccessFromApiKeyTest extends \PHPUnit\Framework\TestCase
{
    protected RequestAccessFromApiKey $fixture;

    public function setUp(): void
    {
        $this->fixture = FixtureRequestAccessFromApiKey::get();
    }

    public function testConstruct(): void
    {
        $entity = new RequestAccessFromApiKey(
            FixtureApplication::get()->apiKey,
            300
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(RequestAccessFromApiKey::class, $entity);
    }
}
