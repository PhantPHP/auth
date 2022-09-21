<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\DataStructure;

use Phant\Error\NotCompliant;

final class SslKey extends \Phant\DataStructure\Abstract\Entity
{
	private string $private;
	private string $public;
	
	public function __construct(string $private, string $public)
	{
		$this->private = $private;
		$this->public = $public;
	}
	
	public function getPrivate(): string
	{
		return $this->private;
	}
	
	public function getPublic(): string
	{
		return $this->public;
	}
	
	public function encrypt(string $data): string
	{
		try {
			$success = openssl_private_encrypt(
				$data,
				$encryptedData,
				$this->private
			);
		} catch (\Exception $e) {
			$success = false;
		}
		
		if (!$success) {
			throw new NotCompliant('Encryption invalid, verify private key');
		}
		
		return $encryptedData;
	}
	
	public function decrypt(string $encryptedData): string
	{
		try {
			$success = openssl_public_decrypt(
				$encryptedData,
				$data,
				$this->public
			);
		} catch (\Exception $e) {
			$success = false;
		}
		
		if (!$success) {
			throw new NotCompliant('Decryption invalid, verify encrypted data or public key');
		}
		
		return $data;
	}
}
