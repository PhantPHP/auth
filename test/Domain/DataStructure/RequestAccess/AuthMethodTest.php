<?php

declare(strict_types=1);

namespace Test\Domain\DataStructure\RequestAccess;

use Phant\Auth\Domain\Entity\RequestAccess\AuthMethod;

final class AuthMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testCases(): void
    {
        $this->assertCount(3, AuthMethod::cases());
    }

    public function testGetLabel(): void
    {
        $this->assertIsString(AuthMethod::ApiKey->getLabel());
        $this->assertIsString(AuthMethod::Otp->getLabel());
        $this->assertIsString(AuthMethod::ThirdParty->getLabel());
    }

    public function testIs(): void
    {
        $result = AuthMethod::ApiKey->is(AuthMethod::ApiKey);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testIsDifferent(): void
    {
        $result = AuthMethod::ApiKey->is(AuthMethod::Otp);

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);
    }
}
