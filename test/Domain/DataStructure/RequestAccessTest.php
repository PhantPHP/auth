<?php

declare(strict_types=1);

namespace Test\Domain\DataStructure;

use Phant\Auth\Domain\Entity\{
    Application,
    RequestAccessFromApiKey,
    User,
};
use Phant\Auth\Domain\Entity\RequestAccess\{
    AuthMethod,
    Id,
    Otp,
    State,
    Token,
};
use Phant\Auth\Fixture\DataStructure\{
    Application as FixtureApplication,
    RequestAccessFromApiKey as FixtureRequestAccessFromApiKey,
    SslKey as FixtureSslKey,
    User as FixtureUser,
};
use Phant\Error\NotAuthorized;
use Phant\Error\NotCompliant;

final class RequestAccessTest extends \PHPUnit\Framework\TestCase
{
    protected RequestAccessFromApiKey $fixture;

    public function setUp(): void
    {
        $this->fixture = FixtureRequestAccessFromApiKey::get();
    }

    public function testGetId(): void
    {
        $value = $this->fixture->id;

        $this->assertIsObject($value);
        $this->assertInstanceOf(Id::class, $value);
    }

    public function testGetApplication(): void
    {
        $value = $this->fixture->application;

        $this->assertNull($value);
    }

    public function testSetApplication(): void
    {
        $this->fixture->setApplication(FixtureApplication::get());

        $value = $this->fixture->application;

        $this->assertIsObject($value);
        $this->assertInstanceOf(Application::class, $value);
        $this->assertEquals(FixtureApplication::get(), $value);
    }

    public function testSetApplicationInvalid(): void
    {
        $this->expectException(NotAuthorized::class);

        $this->fixture->setApplication(FixtureApplication::get());
        $this->fixture->setApplication(FixtureApplication::get());
    }

    public function testGetUser(): void
    {
        $value = $this->fixture->user;

        $this->assertNull($value);
    }

    public function testSetUser(): void
    {
        $this->fixture->setUser(FixtureUser::get());

        $value = $this->fixture->user;

        $this->assertIsObject($value);
        $this->assertInstanceOf(User::class, $value);
        $this->assertEquals(FixtureUser::get(), $value);
    }

    public function testSetUserInvalid(): void
    {
        $this->expectException(NotAuthorized::class);

        $this->fixture->setUser(FixtureUser::get());
        $this->fixture->setUser(FixtureUser::get());
    }

    public function testGetAuthMethod(): void
    {
        $value = $this->fixture->authMethod;

        $this->assertIsObject($value);
        $this->assertInstanceOf(AuthMethod::class, $value);
    }

    public function testGetState(): void
    {
        $value = $this->fixture->state;

        $this->assertIsObject($value);
        $this->assertInstanceOf(State::class, $value);
    }

    public function testCanBeSetStateTo(): void
    {
        $result = $this->fixture->canBeSetStateTo(State::Verified);

        $this->assertIsBool($result);
        $this->assertEquals(true, $result);
    }

    public function testSetState(): void
    {
        $entity = $this->fixture->setState(State::Verified);

        $this->assertIsObject($entity);
        $this->assertInstanceOf(RequestAccessFromApiKey::class, $entity);

        $this->assertIsObject($entity->state);
        $this->assertInstanceOf(State::class, $entity->state);
        $this->assertEquals(State::Verified, $entity->state);
    }

    public function testSetStateInvalid(): void
    {
        $this->expectException(NotAuthorized::class);

        $entity = $this->fixture->setState(State::Requested);
    }

    public function testGetLifetime(): void
    {
        $value = $this->fixture->lifetime;

        $this->assertIsInt($value);
    }

    public function testGetExpiration(): void
    {
        $value = $this->fixture->expiration;

        $this->assertIsInt($value);
    }

    public function testTokenizeIdAndUntokenizeId(): void
    {
        $result = $this->fixture->tokenizeId(FixtureSslKey::get());

        $this->assertIsObject($result);
        $this->assertInstanceOf(Token::class, $result);

        $entity = $this->fixture->untokenizeId($result, FixtureSslKey::get());

        $this->assertIsObject($entity);
        $this->assertInstanceOf(Id::class, $entity);
    }

    public function testTokenizeIdInvalid(): void
    {
        $this->expectException(NotCompliant::class);

        $this->fixture->tokenizeId(FixtureSslKey::getInvalid());
    }

    public function testUntokenizeIdInvalid(): void
    {
        $this->expectException(NotCompliant::class);

        $this->fixture->untokenizeId(
            $this->fixture->tokenizeId(FixtureSslKey::get()),
            FixtureSslKey::getInvalid()
        );
    }

    public function testUntokenizeIdExpired(): void
    {
        $this->expectException(NotCompliant::class);

        $this->fixture->untokenizeId(
            FixtureRequestAccessFromApiKey::getExpired()->tokenizeId(FixtureSslKey::get()),
            FixtureSslKey::get()
        );
    }
}
