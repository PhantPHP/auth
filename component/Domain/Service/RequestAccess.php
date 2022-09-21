<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Service;

use Phant\Auth\Domain\Port\RequestAccess as PortRequestAccess;

use Phant\Auth\Domain\DataStructure\{
	RequestAccess as EntityRequestAccess,
	SslKey,
};
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	Id,
	Token,
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
	
	public function get(string|Id $id): EntityRequestAccess
	{
		if (is_string($id)) $id = new Id($id);
		
		return $this->repository->get($id);
	}
	
	public function getToken(EntityRequestAccess $requestAccess): Token
	{
		return $requestAccess->tokenizeId($this->sslKey);
	}
	
	public function getFromToken(string|Token $token): EntityRequestAccess
	{
		if (is_string($token)) $token = new Id($token);
		
		$id = EntityRequestAccess::untokenizeId($token, $this->sslKey);
		
		return $this->get($id);
	}
}
