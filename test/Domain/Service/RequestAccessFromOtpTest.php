<?php

declare(strict_types=1);

namespace Test\Domain\Service;

use Phant\Auth\Domain\Entity\{
    AccessToken,
    RequestAccessFromOtp,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    Token,
};
use Phant\Auth\Domain\Service\RequestAccessFromOtp as ServiceRequestAccessFromOtp;

use Phant\Auth\Fixture\DataStructure\{
    Application as FixtureApplication,
    User as FixtureUser,
    RequestAccessFromOtp as FixtureRequestAccessFromOtp,
};
use Phant\Auth\Fixture\Port\OtpSender as FixturePortOtpSender;
use Phant\Auth\Fixture\Service\{
    RequestAccessFromOtp as FixtureServiceRequestAccessFromOtp,
};
use Phant\Cache\File as SimpleCache;

use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

final class RequestAccessFromOtpTest extends \PHPUnit\Framework\TestCase
{
    protected ServiceRequestAccessFromOtp $service;
    protected Token $fixture;
    protected SimpleCache $cache;

    public function setUp(): void
    {
        $this->service = (new FixtureServiceRequestAccessFromOtp())();
        $this->fixture = $this->service->generate(
            FixtureApplication::get(),
            FixtureUser::get()
        );
        $this->cache = new SimpleCache(realpath(__DIR__ . '/../../../test/storage/'), 'user-notification');
    }

    public function testGenerate(): void
    {
        $this->assertIsObject($this->fixture);
        $this->assertInstanceOf(Token::class, $this->fixture);
    }

    public function testGenerateInvalid(): void
    {
        $this->expectException(NotCompliant::class);

        $this->service->generate(
            FixtureApplication::get(),
            FixtureUser::get(),
            0
        );
    }

    public function testVerify(): void
    {
        $otp = $this->cache->get((string)$this->fixture);

        $result = $this->service->verify(
            (string) $this->fixture,
            $otp
        );

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testVerifyInvalid(): void
    {
        $result = $this->service->verify(
            $this->fixture,
            '000000'
        );

        $this->assertIsBool($result);
        $this->assertEquals(false, $result);

        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(2, $result);

        $result = $this->service->verify(
            $this->fixture,
            '000000'
        );
        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(1, $result);

        $result = $this->service->verify(
            $this->fixture,
            '000000'
        );
        $result = $this->service->getNumberOfRemainingAttempts(
            $this->fixture
        );
        $this->assertEquals(0, $result);
    }

    public function testVerifyNotAuthorized(): void
    {
        $this->expectException(NotAuthorized::class);

        $otp = $this->cache->get((string)$this->fixture);

        $result = $this->service->verify(
            $this->fixture,
            $otp
        );

        $otp = $this->cache->get((string)$this->fixture);

        $result = $this->service->verify(
            $this->fixture,
            $otp
        );
    }

    public function testGetNumberOfRemainingAttempts(): void
    {
        $result = $this->service->getNumberOfRemainingAttempts(
            (string) $this->fixture
        );

        $this->assertIsInt($result);
        $this->assertEquals(3, $result);
    }

    public function testGetAccessToken(): void
    {
        $otp = $this->cache->get((string)$this->fixture);

        $result = $this->service->verify(
            $this->fixture,
            $otp
        );

        $entity = $this->service->getAccessToken(
            (string) $this->fixture
        );

        $this->assertIsObject($entity);
        $this->assertInstanceOf(AccessToken::class, $entity);
    }
}
