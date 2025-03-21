<?php

declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\Entity\AccessToken;
use Phant\Auth\Domain\Entity\Application\ApiKey;
use Phant\Auth\Domain\Service\RequestAccessFromApiKey as ServiceRequestAccessFromApiKey;
use Phant\Auth\Fixture\DataStructure\Application as FixtureApplication;
use Phant\Auth\Fixture\Service\RequestAccessFromApiKey as FixtureServiceRequestAccessFromApiKey;
use Phant\Error\NotFound;

final class RequestAccessFromApiKeyTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceRequestAccessFromApiKey $service;

    public function setUp(): void
    {
        $this->service = (new FixtureServiceRequestAccessFromApiKey())();
    }

    public function testGetAccessToken(): void
    {
        $entity = $this->service->getAccessToken(
            (string) FixtureApplication::get()->apiKey
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(AccessToken::class, $entity);
    }

    public function testGetAccessTokenNotFound(): void
    {
        $this->expectException(NotFound::class);

        $entity = $this->service->getAccessToken(
            ApiKey::generate()
        );

        $this->assertNull($entity);
    }
}
