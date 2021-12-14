# Xero PHP oAuth2
## Current release of SDK with oAuth 2 support
[![Latest Stable Version](https://poser.pugx.org/xeroapi/xero-php-oauth2/v/stable)](https://packagist.org/packages/xeroapi/xero-php-oauth2)

This release only supports oAuth2 authentication and the following API sets.
* accounting
* fixed asset 
* projects
* payroll AU
* payroll UK
* payroll NZ
* files 

## SDK Documentation

All third party libraries dependencies managed with Composer.

[SDK reference for Accounting](https://xeroapi.github.io/xero-php-oauth2/docs/v2/accounting/index.html) methods with runnable code examples.

[Model reference for Accounting](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/accounting) for all models.

[SDK reference for Identity](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/identity) for all methods and models.

[SDK reference for Fixed Asset](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/assets) for all methods and models.

[SDK reference for Projects](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/project) for all methods and models.

[SDK reference for Australian Payroll](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/payrollau) for all methods and models.

[SDK reference for UK Payroll](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/payrolluk) for all methods and models.

[SDK reference for NZ Payroll](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/payrollnz) for all methods and models.

[SDK reference for Files](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc/file) for all methods and models.

## Requirements

PHP 5.6 and later

## Changes in version 2.x

### Methods to access Dates in Accounting have changed 
Both our Accounting and AU Payroll APIs use [Microsoft .NET JSON format](https://developer.xero.com/documentation/api/requests-and-responses#JSON) i.e. "\/Date(1439434356790)\/". Our other APIs use standard date formatting i.e. "2020-03-24T18:43:43.860852". Building our SDKs from OpenAPI specs with such different date formats has been challenging.

For this reason, we've decided dates in MS .NET JSON format will be  strings with NO date or date-time format in our OpenAPI specs. This means developers wanting to use our OpenAPI specs with code generators won't run into deserialization issues trying to handle MS .NET JSON format dates.

The side effect is accounting and AU payroll models now have two getter methods. For example, getDateOfBirth() returns the string "\/Date(1439434356790)\/" while getDateOfBirthAsDate() return a standard date "2020-05-14". Since you can override methods in Java setDateOfBirth() can accept a String or a LocalDate. 

```php
//Get account by id
$result = $apiInstance->getAccount($xeroTenantId,$accountId); 	

// display formatted date
echo($result->getAccounts()[0]->getUpdatedDateUtcAsDate()->format('Y-m-d H:i:s') ):

// display string in MS .NET JSON format \/Date(1439434356790)\/
echo($result->getAccounts()[0]->getUpdatedDateUtc() ):

//When setting a date for accounting or AU Payroll, remember to use the correct method
// For example setStartDate has a 2nd  method with "AsDate" if you wish to pass a native date
// This converts the date object to MS DateFormat
$leaveapplication->setStartDateAsDate(new DateTime('2020-05-02'));

// You'll get an error from the AU Payroll API if you try setStartDate("2020-05-02")
// But if you want to pass in MS Dateformat, this string will work.
$leaveapplication->setStartDate("/Date(1547164800000+0000)/");

```

## Getting Started

### Create a Xero App
Follow these steps to create your Xero app

* Create a [free Xero user account](https://www.xero.com/us/signup/api/) (if you don't have one)
* Login to [Xero developer center](https://developer.xero.com/myapps)
* Click "New App" link
* Enter your App name, company url, privacy policy url.
* Enter the redirect URI (something like http://localhost:8888/pathToApp/callback.php)
* Agree to terms and condition and click "Create App".
* Click "Generate a secret" button.
* Copy your client id and client secret and **save for use later**.
* Click the "Save" button. Your secret is now hidden.


## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), and add the xero-php-oauth2 sdk to your `composer.json`:

Navigate to where your composer.json file is and run the command
```
composer require xeroapi/xero-php-oauth2
```

If no `composer.json` file exists, create one by running the following command. You'll need [Composer](http://getcomposer.org/) installed.
```
composer init
```

### Configure PHPStorm
We've received feedback that PHPStorm IDE default file size is too small to load the AccountingApi class.

"PHPStorm seems unable to resolve the XeroAPI\XeroPHP\Api\AccountingApi class. It just shows Undefined class 'AccountingApi' and therefore can't autocomplete any of the methods etc."

To fix this, add the following to the idea.properties file to increase this limit to 5000 kilobytes

idea.max.intellisense.filesize=5000

Instructions here for [configuring PHPStorm](https://www.jetbrains.com/help/phpstorm/tuning-the-ide.html#configure-platform-properties) platform properties on Mac/Windows/Linux


### Laravel
 Xero doesn't offer support on how to use of our SDKs in different frameworks, etc.  We had a recommendation by Matt @hailwood in our developer community. His company integrates xero-php-oauth2 and Laravel using the following package.
* https://github.com/webfox/laravel-xero-oauth2
* https://packagist.org/packages/webfox/laravel-xero-oauth2


## How to use xero-php-oauth2
Below is starter code with the oAuth 2 flow.  You can copy/paste the code below into 4 separate PHP files and substitute your **ClientId, ClientSecret and RedirectURI**

### [Download starter code](https://github.com/XeroAPI/xero-php-oauth2-starter)

#### Important 
The RedirectURI (something like http://localhost:8888/pathToApp/callback.php) in your code needs to point to the callback.php file and match the RedirectURI you set when creating your Xero app. 

1. Point your browser to authorization.php, you'll be redirected to Xero where you'll login and select a Xero org to authorize.  We  recommend the **Demo Company** org, since this code will modify data in the org you connect to. 
2. Once complete, you'll be returned to your app to the redirect URI which should point to the callback.php. 
3. In callback.php, you'll obtain an access token which we'll use in authorizedResource.php to create, read, update and delete information in the connected Xero org.

### authorization.php

```php
<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('storage.php');

  // Storage Class uses sessions for storing access token (demo only)
  // you'll need to extend to your Database for a scalable solution
  $storage = new StorageClass();

  session_start();

  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__YOUR_CLIENT_ID__',   
    'clientSecret'            => '__YOUR_CLIENT_SECRET__',
    'redirectUri'             => 'http://localhost:8888/pathToApp/callback.php',
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
    'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
  ]);

  // Scope defines the data your app has permission to access.
  // Learn more about scopes at https://developer.xero.com/documentation/oauth2/scopes
  $options = [
      'scope' => ['openid email profile offline_access accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
  ];

  // This returns the authorizeUrl with necessary parameters applied (e.g. state).
  $authorizationUrl = $provider->getAuthorizationUrl($options);

  // Save the state generated for you and store it to the session.
  // For security, on callback we compare the saved state with the one returned to ensure they match.
  $_SESSION['oauth2state'] = $provider->getState();

  // Redirect the user to the authorization URL.
  header('Location: ' . $authorizationUrl);
  exit();
?>
```

### callback.php

```php
<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('storage.php');

  // Storage Classe uses sessions for storing token > extend to your DB of choice
  $storage = new StorageClass();  

  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__YOUR_CLIENT_ID__',   
    'clientSecret'            => '__YOUR_CLIENT_SECRET__',
    'redirectUri'             => 'http://localhost:8888/pathToApp/callback.php', 
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
    'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
  ]);
   
  // If we don't have an authorization code then get one
  if (!isset($_GET['code'])) {
    echo "Something went wrong, no authorization code found";
    exit("Something went wrong, no authorization code found");

  // Check given state against previously stored one to mitigate CSRF attack
  } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    echo "Invalid State";
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
  } else {
  
    try {
      // Try to get an access token using the authorization code grant.
      $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
      ]);
           
      $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$accessToken->getToken() );
      $identityApi = new XeroAPI\XeroPHP\Api\IdentityApi(
        new GuzzleHttp\Client(),
        $config
      );
       
      $result = $identityApi->getConnections();

      // Save my tokens, expiration tenant_id
      $storage->setToken(
          $accessToken->getToken(),
          $accessToken->getExpires(),
          $result[0]->getTenantId(),  
          $accessToken->getRefreshToken(),
          $accessToken->getValues()["id_token"]
      );
   
      header('Location: ' . './authorizedResource.php');
      exit();
     
    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
      echo "Callback failed";
      exit();
    }
  }
?>
```

### storage.php

```php
<?php
class StorageClass
{
	function __construct() {
		if( !isset($_SESSION) ){
        	$this->init_session();
    	}
   	}

   	public function init_session(){
    	session_start();
	}

    public function getSession() {
    	return $_SESSION['oauth2'];
    }

 	public function startSession($token, $secret, $expires = null)
	{
       	session_start();
	}

	public function setToken($token, $expires = null, $tenantId, $refreshToken, $idToken)
	{    
	    $_SESSION['oauth2'] = [
	        'token' => $token,
	        'expires' => $expires,
	        'tenant_id' => $tenantId,
	        'refresh_token' => $refreshToken,
	        'id_token' => $idToken
	    ];
	}

	public function getToken()
	{
	    //If it doesn't exist or is expired, return null
	    if (empty($this->getSession())
	        || ($_SESSION['oauth2']['expires'] !== null
	        && $_SESSION['oauth2']['expires'] <= time())
	    ) {
	        return null;
	    }
	    return $this->getSession();
	}

	public function getAccessToken()
	{
	    return $_SESSION['oauth2']['token'];
	}

	public function getRefreshToken()
	{
	    return $_SESSION['oauth2']['refresh_token'];
	}

	public function getExpires()
	{
	    return $_SESSION['oauth2']['expires'];
	}

	public function getXeroTenantId()
	{
	    return $_SESSION['oauth2']['tenant_id'];
	}

	public function getIdToken()
	{
	    return $_SESSION['oauth2']['id_token'];
	}

	public function getHasExpired()
	{
		if (!empty($this->getSession())) 
		{
			if(time() > $this->getExpires())
			{
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}
?>
```

### authorizedResource.php

```php
<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('storage.php');

  // Use this class to deserialize error caught
  use XeroAPI\XeroPHP\AccountingObjectSerializer;

  // Storage Classe uses sessions for storing token > extend to your DB of choice
  $storage = new StorageClass();
  $xeroTenantId = (string)$storage->getSession()['tenant_id'];

  if ($storage->getHasExpired()) {
    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => '__YOUR_CLIENT_ID__',
      'clientSecret'            => '__YOUR_CLIENT_SECRET__',
      'redirectUri'             => 'http://localhost:8888/xero-php-oauth2-starter/callback.php',
      'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
      'urlAccessToken'          => 'https://identity.xero.com/connect/token',
      'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
    ]);

    $newAccessToken = $provider->getAccessToken('refresh_token', [
      'refresh_token' => $storage->getRefreshToken()
    ]);

    // Save my token, expiration and refresh token
    $storage->setToken(
        $newAccessToken->getToken(),
        $newAccessToken->getExpires(),
        $xeroTenantId,
        $newAccessToken->getRefreshToken(),
        $newAccessToken->getValues()["id_token"] );
  }

  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );	
  
  $accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
    new GuzzleHttp\Client(),
    $config
  );

  $assetApi = new XeroAPI\XeroPHP\Api\AssetApi(
    new GuzzleHttp\Client(),
    $config
  );  

  $identityApi = new XeroAPI\XeroPHP\Api\IdentityApi(
    new GuzzleHttp\Client(),
    $config
  );  

  $projectApi = new XeroAPI\XeroPHP\Api\ProjectApi(
    new GuzzleHttp\Client(),
    $config
  );  

  $message = "no API calls";
  if (isset($_GET['action'])) {
    if ($_GET["action"] == 1) {
        // Get Organisation details
        $apiResponse = $accountingApi->getOrganisations($xeroTenantId);
        $message = 'Organisation Name: ' . $apiResponse->getOrganisations()[0]->getName();
    } else if ($_GET["action"] == 2) {
        // Create Contact
        try {
            $person = new XeroAPI\XeroPHP\Models\Accounting\ContactPerson;
            $person->setFirstName("John")
                ->setLastName("Smith")
                ->setEmailAddress("john.smith@24locks.com")
                ->setIncludeInEmails(true);

            $arr_persons = [];
            array_push($arr_persons, $person);

            $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName('FooBar')
                ->setFirstName("Foo")
                ->setLastName("Bar")
                ->setEmailAddress("ben.bowden@24locks.com")
                ->setContactPersons($arr_persons);
            
            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);

            $apiResponse = $accountingApi->createContacts($xeroTenantId,$contacts);
            $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getName();
        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $error = AccountingObjectSerializer::deserialize(
                $e->getResponseBody(),
                '\XeroAPI\XeroPHP\Models\Accounting\Error',
                []
            );
            $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
        }

    } else if ($_GET["action"] == 3) {
        $if_modified_since = new \DateTime("2019-01-02T19:20:30+01:00"); // \DateTime | Only records created or modified since this timestamp will be returned
        $if_modified_since = null;
        $where = 'Type=="ACCREC"'; // string
        $where = null;
        $order = null; // string
        $ids = null; // string[] | Filter by a comma-separated list of Invoice Ids.
        $invoice_numbers = null; // string[] |  Filter by a comma-separated list of Invoice Numbers.
        $contact_ids = null; // string[] | Filter by a comma-separated list of ContactIDs.
        $statuses = array("DRAFT", "SUBMITTED");;
        $page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items
        $include_archived = null; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
        $created_by_my_app = null; // bool | When set to true you'll only retrieve Invoices created by your app
        $unitdp = null; // int | e.g. unitdp=4 – You can opt in to use four decimal places for unit amounts

        try {
            $apiResponse = $accountingApi->getInvoices($xeroTenantId, $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
            if (  count($apiResponse->getInvoices()) > 0 ) {
                $message = 'Total invoices found: ' . count($apiResponse->getInvoices());
            } else {
                $message = "No invoices found matching filter criteria";
            }
        } catch (Exception $e) {
            echo 'Exception when calling AccountingApi->getInvoices: ', $e->getMessage(), PHP_EOL;
        }
    } else if ($_GET["action"] == 4) {
        // Create Multiple Contacts
        try {
            $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName('George Jetson')
                ->setFirstName("George")
                ->setLastName("Jetson")
                ->setEmailAddress("george.jetson@aol.com");

            // Add the same contact twice - the first one will succeed, but the
            // second contact will throw a validation error which we'll catch.
            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            array_push($arr_contacts, $contact);
            $contacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);

            $apiResponse = $accountingApi->createContacts($xeroTenantId,$contacts,false);
            $message = 'First contacts created: ' . $apiResponse->getContacts()[0]->getName();

            if ($apiResponse->getContacts()[1]->getHasValidationErrors()) {
                $message = $message . '<br> Second contact validation error : ' . $apiResponse->getContacts()[1]->getValidationErrors()[0]["message"];
            }

        } catch (\XeroAPI\XeroPHP\ApiException $e) {
            $error = AccountingObjectSerializer::deserialize(
                $e->getResponseBody(),
                '\XeroAPI\XeroPHP\Models\Accounting\Error',
                []
            );
            $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
        }
    } else if () {

        // DELETE the org FIRST Connection returned
        $connections = $identityApi->getConnections();
        $id = $connections[0]->getId();
        $result = $identityApi->deleteConnection($id);

    }
  }
?>
<html>
    <body>
        <ul>
            <li><a href="authorizedResource.php?action=1">Get Organisation Name</a></li>
            <li><a href="authorizedResource.php?action=2">Create one Contact</a></li>
            <li><a href="authorizedResource.php?action=3">Get Invoice with Filters</a></li>
            <li><a href="authorizedResource.php?action=4">Create multiple contacts and summarizeErrors</a></li>
            <li><a href="authorizedResource.php?action=5">Delete an organisation connection</a></li>
        </ul>
        <div>
        <?php
            echo($message );
        ?>
        </div>
    </body>
</html>
```

## JWT decoding and Signup with Xero

Looking to implement [Signup with Xero](https://developer.xero.com/documentation/oauth2/sign-in)? We've added built in decoding and verification for both Access tokens and ID token in xero-php-oauth2.

Json Web Tokens (JWT) claims are pieces of information asserted about a subject.

The code below shows how to securely read claims about the access token (a user authentication) and abut the id token (a user's identity & profile).

```php
  // DECODE & VERIFY ACCESS_TOKEN
  $accessToken = (string)$storage->getSession()['token'];
  $jwtAccessTokenClaims = new XeroAPI\XeroPHP\JWTClaims();
  $jwtAccessTokenClaims->decodeAccessToken($accessToken);

  echo($jwtAccessTokenClaims->getNbf());
  echo($jwtAccessTokenClaims->getExp());
  echo($jwtAccessTokenClaims->getIss());
  echo($jwtAccessTokenClaims->getAudValue());
  echo($jwtAccessTokenClaims->getClientId());
  echo($jwtAccessTokenClaims->getAuthTime());
  echo($jwtAccessTokenClaims->getXeroUserId());
  echo($jwtAccessTokenClaims->getGlobalSessionId());
  echo($jwtAccessTokenClaims->getJti());
  echo($jwtAccessTokenClaims->getAuthenticationEventId());
  // scopes are an array therfore we dump not echo them.
  var_dump($jwtAccessTokenClaims->getScope());
  
  //DECODE & VERIFY ID_TOKEN 
  $IdToken = (string)$storage->getSession()['id_token'];
  $jwtIdTokenClaims = new XeroAPI\XeroPHP\JWTClaims();
  $jwtIdTokenClaims->decodeIdToken($IdToken);

  // 13 Claims are available
  echo($jwtIdTokenClaims->getNbf());
  echo($jwtIdTokenClaims->getExp());
  echo($jwtIdTokenClaims->getIss());
  echo($jwtIdTokenClaims->getAudValue());
  echo($jwtIdTokenClaims->getIat());
  echo($jwtIdTokenClaims->getAtHash());
  echo($jwtIdTokenClaims->getSid());
  echo($jwtIdTokenClaims->getSub());
  echo($jwtIdTokenClaims->getAuthTime());
  echo($jwtIdTokenClaims->getPreferredUsername());
  echo($jwtIdTokenClaims->getEmail());
  echo($jwtIdTokenClaims->getGivenName());
  echo($jwtIdTokenClaims->getFamilyName());
```


## Participating in Xero’s developer community
This SDK is one of a number of SDK’s that the Xero Developer team builds and maintains. We are grateful for all the contributions that the community makes. 

Here are a few things you should be aware of as a contributor:
* Xero has adopted the Contributor Covenant [Code of Conduct](https://github.com/XeroAPI/xero-php-oauth2/blob/master/CODE_OF_CONDUCT.md), we expect all contributors in our community to adhere to it
* If you raise an issue then please make sure to fill out the github issue template, doing so helps us help you 
* You’re welcome to raise PRs. As our SDKs are generated we may use your code in the core SDK build instead of merging your code
* We have a [contribution guide](https://github.com/XeroAPI/xero-php-oauth2/blob/master/CONTRIBUTING.md) for you to follow when contributing to this SDK
* Curious about how we generate our SDK’s? Have a [read of our process](https://devblog.xero.com/building-sdks-for-the-future-b79ff726dfd6) and have a look at our [OpenAPISpec](https://github.com/XeroAPI/Xero-OpenAPI)
* This software is published under the [MIT License](https://github.com/XeroAPI/xero-php-oauth2/blob/master/LICENSE)

For questions that aren’t related to SDKs please refer to our [developer support page](https://developer.xero.com/support/).
