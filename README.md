# Auth

## Presentation

The authentication service is intended to manage access (Applications and Users) to applications and APIs.

The application wishing to use this service must first obtain an API key (to be generated).

Authentication service aims to provide an access token allowing access of applications and users.

Obtaining the access token is subject to various methods.

The access token has a limited lifetime.
It embeds data relating to its applicant (application, user).


## Technologies used

- `PHP 8.1`
- `Composer` for dependencies management (PHP)


## Installation

`composer install`


## Request access

For each use case the following setup is required.

```php
use Phant\Auth\Domain\Service\AccessToken as ServiceAccessToken;
use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;
use Phant\Auth\FixtDomainure\DataStructure\SslKey;
use App\RepositoryRequestAccess;


// Config

$sslKey = new SslKey('private key', 'public key');
$repositoryRequestAccess = new RepositoryRequestAccess();


// Build services

$serviceRequestAccess = new ServiceRequestAccess(
	$repositoryRequestAccess,
	$sslKey
);

$serviceAccessToken = return new ServiceAccessToken(
	$sslKey,
	$serviceRequestAccess
)
```

### From API key

Process :

1. The application requests an access token by providing its API key,
2. The service provides an access token.

```php
use Phant\Auth\Domain\Service\RequestAccessFromApiKey as ServiceRequestAccessFromApiKey;
use App\RepositoryApplication;


// Config

$repositoryApplication = new RepositoryApplication();


// Build services

$serviceRequestAccessFromApiKey = new ServiceRequestAccessFromApiKey(
	$serviceRequestAccess,
	$serviceAccessToken,
	$repositoryApplication
);


// Obtain API key from application

/* @todo */
$apiKey = 'XXXXXXXX.XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';


// Request access token

$accessToken = $serviceRequestAccessFromApiKey->getAccessToken($apiKey);
```


### From OTP

Process :

1. The application asks the user to authenticate himself by providing his contact details (last name, first name and e-mail address),
2. The application generates an access request by providing its identity and the user's contact details (last name, first name and e-mail address),
3. The service generates an OTP and requests its sending to the user,
4. The user receives an OTP,
5. The application retrieves the OTP from the user,
6. The user transmits the received OTP to the application,
7. The application verifies the OTP with the service,
8. The application requests an access token,
9. The service provides an access token.

The OTP is sent to user by your own OtpSender service (e-mail, SMS, etc.).

```php
use Phant\Auth\Domain\Service\RequestAccessFromOtp as ServiceRequestAccessFromOtp;
use Phant\Auth\Domain\DataStructure\Application;
use Phant\Auth\Domain\DataStructure\User;
use App\OtpSender;


// Config

$repositoryApplication = new RepositoryApplication();
$OtpSender = new OtpSender();


// Build services

$serviceRequestAccessFromOtp = new ServiceRequestAccessFromOtp(
	$serviceRequestAccess,
	$serviceAccessToken,
	$OtpSender
);


// Request access token

$user = new User(
	'john.doe@domain.ext',
	'John',
	'DOE'
);

$application = new Application(
	'eb7c9c44-32c2-4e88-8410-4ebafb18fdf7',
	'My app',
	'https://domain.ext/image.ext'
);

$requestAccessToken = $serviceRequestAccessFromOtp->generate($user, $application);


// Obtain OTP from user

/* @todo */
$otp = '123456';


// Verify OTP

$isValid = $serviceRequestAccessFromOtp->verify($otp);

if ( ! $isValid) {
	$numberOfAttemptsRemaining = $serviceRequestAccessFromOtp->numberOfAttemptsRemaining($requestAccessToken);
}


// Get access token

$accessToken = $serviceRequestAccessFromOtp->getAccessToken($requestAccessToken);
```


### From third party

Process :

1. The application generates an access request by providing its identity,
2. The service generates an Access-Request and returns an Access-Request Token,
3. The application forwards the authentication request to the third party service by passing the access request token,
4. The user authenticates with the third-party authentication service,
5. The application retrieves the user authentication result,
6. The application declares the authentication result,
7. The service takes note of the authentication.
8. The service provides an access token.

```php
use Phant\Auth\Domain\Service\RequestAccessFromThirdParty as ServiceRequestAccessFromThirdParty;
use Phant\Auth\Domain\DataStructure\Application;
use Phant\Auth\Domain\DataStructure\User;
use App\RepositoryRequestAccess;


// Config

$repositoryApplication = new RepositoryApplication();
$OtpSender = new OtpSender();


// Build services

$serviceRequestAccessFromThirdParty = new ServiceRequestAccessFromThirdParty(
	$serviceRequestAccess,
	$serviceAccessToken
);


// Request access token

$application = new Application(
	'eb7c9c44-32c2-4e88-8410-4ebafb18fdf7',
	'My app',
	'https://domain.ext/image.ext'
);

$requestAccessToken = $serviceRequestAccessFromThirdParty->generate($application);


// Request third party auth with requestAccessToken

/* @todo */


// Obtain authentication status

/* @todo */
$isAuthorized = true;


// Obtain user data

/* @todo */
$user = new User(
	'john.doe@domain.ext',
	'John',
	'DOE'
);


// Set auth status

$serviceRequestAccessFromThirdParty->setStatus($requestAccessToken, $user, $isAuthorized);


// Get access token

$accessToken = $serviceRequestAccessFromThirdParty->getAccessToken($requestAccessToken);
```


## Access token

The access token is a JWT.

For each use case the following setup is required.

```php
use Phant\Auth\Domain\Service\AccessToken as ServiceAccessToken;
use Phant\Auth\Domain\Service\RequestAccess as ServiceRequestAccess;
use Phant\Auth\FixtDomainure\DataStructure\SslKey;
use App\RepositoryRequestAccess;


// Config

$sslKey = new SslKey('private key', 'public key');
$repositoryRequestAccess = new RepositoryRequestAccess();


// Build services

$serviceRequestAccess = new ServiceRequestAccess(
	$repositoryRequestAccess,
	$sslKey
);

$serviceAccessToken = return new ServiceAccessToken(
	$sslKey,
	$serviceRequestAccess
)


// An access token

$accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhcHAiOnsiaWQiOiJjMjI4MDI1OC03OGU5LTQ4ZmQtOTA1Zi0yYzhlMDIzYWNiOWMiLCJuYW1lIjoiRmxhc2hwb2ludCIsImxvZ28iOiJodHRwczpcL1wvdmlhLnBsYWNlaG9sZGVyLmNvbVwvNDAweDIwMD90ZXh0PUZsYXNocG9pbnQiLCJhcGlfa2V5IjoiZnc5TEFJcFkuclA2b2d5VlNRdEx1OWRWMXBqOTR2WG56ekVPNXNISldHeHdhNWMxZzZMa3owNlo5dGNuc21GNFNieVRqeURTaCJ9LCJ1c2VyIjp7ImVtYWlsX2FkZHJlc3MiOiJqb2huLmRvZUBkb21haW4uZXh0IiwibGFzdG5hbWUiOiJET0UiLCJmaXJzdG5hbWUiOiJKb2huIiwicm9sZSI6bnVsbH0sImlhdCI6MTY2MzY4NDM3MCwiZXhwIjoxNjYzNjk1MTcwfQ.a-wJ_T1ENG58zCw2X7oP2oZrziZRP_m0rOOkUkC2axAsx7O72ebGjQja-iry-lFvd1PF48BxejQw69LPUQKrx1Tb9oQ_8VqMhU97nR8Jd5v2jlWIA7CP2H9voQLE5ybHpqFO2IzgPf2MurzwXQ0tlSeiRbQzHLzMBbWhcQLU4aI';
```

### JWT decrypt method

The application may need the public key for the following uses :
- check the integrity of the token,
- extract data from the token.

```php
use Phant\DataStructure\Token\Jwt;
use Phant\Error\NotCompliant;

$publicKey = $serviceAccessToken->getPublicKey();

try {
	$payLoad = (new Jwt($accessToken))->decode(publicKey);
} catch (NotCompliant $e) {
	
}
```


### Verification

The application can verify the integrity of the token with the service.

```php
use Phant\Auth\Domain\DataStructure\Application;

$application = new Application(
	'eb7c9c44-32c2-4e88-8410-4ebafb18fdf7',
	'My app',
	'https://domain.ext/image.ext'
);

$isValid = $serviceAccessToken->check($accessToken, $application);
```


### Get user infos

The app can get the token user data from the service.

```php
use Phant\Auth\Domain\DataStructure\Application;

$application = new Application(
	'eb7c9c44-32c2-4e88-8410-4ebafb18fdf7',
	'My app',
	'https://domain.ext/image.ext'
);

$userInfos = $serviceAccessToken->getUserInfos($accessToken);
```
