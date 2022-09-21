<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess;
use Phant\Auth\Domain\DataStructure\RequestAccess\{
	Otp,
	Token,
};

interface UserNotification
{
	public function sendOtpFromRequestAccess(Token $requestAccessToken, RequestAccess $requestAccess, Otp $otp): void;
}
