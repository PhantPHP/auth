<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Port\RequestAccess as PortRequestAccess;

use Phant\Auth\Domain\DataStructure\RequestAccess as EntityRequestAccess;
use Phant\Auth\Domain\DataStructure\Value\{
	IdRequestAccess,
	RequestAccessToken,
	SslKey,
};

final class RequestAccess
{
	protected PortRequestAccess $repository;
	protected SslKey $sslKey;
	
	public function __construct(
		PortRequestAccess $repository,
		SslKey $sslKey
	)
	{
		$this->repository = $repository;
		$this->sslKey = $sslKey;
	}
	
	public function set(EntityRequestAccess $requestAccess): void
	{
		$this->repository->set($requestAccess);
	}
	
	public function get(string|IdRequestAccess $id): EntityRequestAccess
	{
		if (is_string($id)) $id = new IdRequestAccess($id);
		
		return $this->repository->get($id);
	}
	
	public function getToken(EntityRequestAccess $requestAccess): RequestAccessToken
	{
		return $requestAccess->tokenizeId($this->sslKey);
	}
	
	public function getFromToken(string|RequestAccessToken $token): EntityRequestAccess
	{
		if (is_string($token)) $token = new IdRequestAccess($token);
		
		$id = EntityRequestAccess::untokenizeId($token, $this->sslKey);
		
		return $this->get($id);
	}
}
