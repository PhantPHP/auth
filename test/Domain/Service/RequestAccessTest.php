<?php

declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\Entity\RequestAccessFromOtp;
use Phant\Auth\Domain\Entity\RequestAccess\Token;
use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;
use Phant\Auth\Fixture\DataStructure\RequestAccessFromOtp as FixtureRequestAccessFromOtp;
use Phant\Auth\Fixture\Service\RequestAccess as FixtureServiceRequestAccess;

final class RequestAccessTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceRequestAccess $service;
    protected RequestAccessFromOtp $fixture;

    public function setUp(): void
    {
        $this->service = (new FixtureServiceRequestAccess())();
        $this->fixture = FixtureRequestAccessFromOtp::get();
    }

    public function testSet(): void
    {
        $this->service->set(
            $this->fixture
        );

        $this->addToAssertionCount(1);
    }

    public function testGet(): void
    {
        $this->service->set(
            $this->fixture
        );

        $entity = $this->service->get(
            (string) $this->fixture->id
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
    }

    public function testGetToken(): void
    {
        $this->service->set(
            $this->fixture
        );

        $value = $this->service->getToken(
            $this->fixture
        );

        $this->assertIsObject($value);
        $this->assertInstanceOf(Token::class, $value);
    }

    public function testGetFromToken(): void
    {
        $this->service->set(
            $this->fixture
        );

        $token = $this->service->getToken(
            $this->fixture
        );

        $entity = $this->service->getFromToken(
            (string)$token
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
    }
}
