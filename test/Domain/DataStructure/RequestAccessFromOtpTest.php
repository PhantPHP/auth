<?php

declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\Entity\RequestAccessFromOtp;
use Phant\Auth\Domain\Entity\RequestAccess\{
    Id,
    Otp,
    State,
};
use Phant\Auth\Fixture\DataStructure\{
    Application as FixtureApplication,
    RequestAccessFromOtp as FixtureRequestAccessFromOtp,
    User as FixtureUser,
};
use Phant\Error\NotAuthorized;

final class RequestAccessFromOtpTest extends \PHPUnit\Framework\TestCase
{
    protected RequestAccessFromOtp $fixture;

    public function setUp(): void
    {
        $this->fixture = FixtureRequestAccessFromOtp::get();
    }

    public function testConstruct(): void
    {
        $entity = new RequestAccessFromOtp(
            FixtureApplication::get(),
            FixtureUser::get(),
            3,
            900
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(RequestAccessFromOtp::class, $entity);
    }

    public function testGetOtp(): void
    {
        $value = $this->fixture->otp;

        $this->assertIsObject($value);
        $this->assertInstanceOf(Otp::class, $value);
    }

    public function testCheckOtp(): void
    {
        $result = $this->fixture->checkOtp(
            $this->fixture->otp
        );

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testGetNumberOfRemainingAttempts(): void
    {
        $result = $this->fixture->getNumberOfRemainingAttempts();

        $this->assertIsInt($result);
        $this->assertEquals(3, $result);
    }

    public function testCheckOtpNotAuthorized(): void
    {
        $this->expectException(NotAuthorized::class);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(2, $result);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(1, $result);

        $result = $this->fixture->checkOtp(
            '000000'
        );
        $this->assertEquals(false, $result);

        $result = $this->fixture->getNumberOfRemainingAttempts();
        $this->assertEquals(0, $result);

        $this->fixture->checkOtp(
            '000000'
        );
    }
}
