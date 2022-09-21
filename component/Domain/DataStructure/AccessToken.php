<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Auth\Domain\DataStructure\{
	Application,
	SslKey,
	User,
};
use Phant\Auth\Domain\DataStructure\AccessToken\{
	Expire,
	Id,
	Jwt,
};
use Phant\Auth\Domain\Serialize\{
	Application as SerializeApplication,
	User as SerializeUser,
};

final class AccessToken extends \Phant\DataStructure\Abstract\Entity
{
	public const PAYLOAD_KEY_APP = 'app';
	public const PAYLOAD_KEY_USER = 'user';
	
	protected string $value;
	protected Expire $expire;
	
	public function __construct(string $value, int $lifetime)
	{
		$this->value = $value;
		$this->expire = new Expire(date('Y-m-d', time() + $lifetime));
	}
	
	public function getValue(): string
	{
		return $this->value;
	}
	
	public function getExpire(): Expire
	{
		return $this->expire;
	}
	
	public function __toString(): string
	{
		return $this->getValue();
	}
	
	public function check(SslKey $sslKey, Application $application): bool
	{
		try {
			
			$payload = (new Jwt($this->value))->decode($sslKey->getPublic());
			
			$id = $payload[ self::PAYLOAD_KEY_APP ]->id ?? null;
			
			if (!$id) return false;
			
			if (!$application->isHisId($id)) return false;
			
		} catch (\Exception $e) {
			return false;
		}
		
		return true;
	}
	
	public function getPayload(SslKey $sslKey): ?array
	{
		try {
			
			$payLoad = (new Jwt($this->value))->decode($sslKey->getPublic());
			
			return $payLoad;
			
		} catch (\Exception $e) {
			return null;
		}
	}
	
	public static function generate(SslKey $sslKey, Application $application, ?User $user, int $lifetime): self
	{
		$payload = [
			self::PAYLOAD_KEY_APP => SerializeApplication::serialize($application),
		];
		
		if ($user) {
			$payload[ self::PAYLOAD_KEY_USER ] = SerializeUser::serialize($user);
		}
		
		return new self((string)Jwt::encode($sslKey->getPrivate(), $payload, $lifetime), $lifetime);
	}
}
