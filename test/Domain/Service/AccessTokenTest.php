<?php

declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\Entity\AccessToken;
use Phant\Auth\Domain\Service\AccessToken as ServiceAccessToken;
use Phant\Auth\Fixture\DataStructure\{
    AccessToken as FixtureAccessToken,
    Application as FixtureApplication,
    RequestAccessFromOtp as FixtureRequestAccessFromOtp,
};
use Phant\Auth\Fixture\Service\AccessToken as FixtureServiceAccessToken;
use Phant\Error\NotAuthorized;

final class AccessTokenTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceAccessToken $service;
    protected AccessToken $fixture;

    public function setUp(): void
    {
        $this->service = (new FixtureServiceAccessToken())();
        $this->fixture = FixtureAccessToken::get();
    }

    public function testGetPublicKey(): void
    {
        $value = $this->service->getPublicKey();

        $this->assertIsString($value);
    }

    public function testCheck(): void
    {
        $value = $this->service->check(
            (string)$this->fixture,
            FixtureApplication::get()
        );

        $this->assertIsBool($value);
        $this->assertEquals(true, $value);
    }

    public function testGetPayload(): void
    {
        $value = $this->service->getPayload(
            (string)$this->fixture
        );

        $this->assertIsArray($value);
    }

    public function testGetFromToken(): void
    {
        $entity = $this->service->getFromToken(
            FixtureRequestAccessFromOtp::getVerified()
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(AccessToken::class, $entity);
    }

    public function testGetFromTokenInvalid(): void
    {
        $this->expectException(NotAuthorized::class);

        $entity = $this->service->getFromToken(
            FixtureRequestAccessFromOtp::get()
        );
    }
}
