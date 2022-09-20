<?php
declare(strict_types=1);

namespace Phant\Auth\Domain\Port;

use Phant\Auth\Domain\DataStructure\RequestAccess;
use Phant\Auth\Domain\DataStructure\Value\{
	Otp,
	RequestAccessToken,
};

interface UserNotification
{
	public function sendOtpFromRequestAccess(RequestAccessToken $requestAccessToken, RequestAccess $requestAccess, Otp $otp): void;
}
