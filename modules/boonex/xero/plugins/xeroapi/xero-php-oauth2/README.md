# xero-php-oauth2
[![Latest Stable Version](http://poser.pugx.org/xeroapi/xero-php-oauth2/v)](https://packagist.org/packages/xeroapi/xero-php-oauth2)
[![Total Downloads](http://poser.pugx.org/xeroapi/xero-php-oauth2/downloads)](https://packagist.org/packages/xeroapi/xero-php-oauth2)
[![Github forks](https://img.shields.io/github/forks/XeroAPI/xero-php-oauth2.svg)](https://github.com/XeroAPI/xero-php-oauth2/network)
[![Github stars](https://img.shields.io/github/stars/XeroAPI/xero-php-oauth2.svg)](https://github.com/XeroAPI/xero-php-oauth2/stargazers) [![License](http://poser.pugx.org/xeroapi/xero-php-oauth2/license)](https://packagist.org/packages/xeroapi/xero-php-oauth2)

The `xero-php-oauth2` SDK makes it easy for developers to access Xero's APIs in their **PHP** code, and build robust applications and software using small business & general ledger accounting data.
# Table of Contents
- [API Client documentation](#api-client-documentation)
- [Sample Applications](#sample-applications)
- [Xero Account Requirements](#xero-account-requirements)
- [Installation](#installation)
- [Authentication](#authentication)
- [Configuration](#configuration)
- [Custom Connections](#custom-connections)
- [API Clients](#api-clients)
- [SDK conventions](#sdk-conventions)
- [Contributing](#contributing)

<hr>

## API Client documentation
This SDK supports full method coverage for the following Xero API sets:

| API Set | Description |
| --- | --- |
| [`Accounting methods`](https://xeroapi.github.io/xero-php-oauth2/docs/v2/accounting/index.html) | The Accounting API exposes accounting functions of the main Xero application *(most commonly used)*
| [Assets](https://xeroapi.github.io/xero-php-oauth2/docs/v2/assets/index.html) | The Assets API exposes fixed asset related functions of the Xero Accounting application |
| [Files](https://xeroapi.github.io/xero-php-oauth2/docs/v2/files/index.html) | The Files API provides access to the files, folders, and the association of files within a Xero organisation |
| [Projects](https://xeroapi.github.io/xero-php-oauth2/docs/v2/projects/index.html) | Xero Projects allows businesses to track time and costs on projects/jobs and report on profitability |
| [Payroll (AU)](https://xeroapi.github.io/xero-php-oauth2/docs/v2/payroll_au/index.html) | The (AU) Payroll API exposes payroll related functions of the payroll Xero application |
| [Payroll (UK)](https://xeroapi.github.io/xero-php-oauth2/docs/v2/payroll_uk/index.html) | The (UK) Payroll API exposes payroll related functions of the payroll Xero application |
| [Payroll (NZ)](https://xeroapi.github.io/xero-php-oauth2/docs/v2/payroll_nz/index.html) | The (NZ) Payroll API exposes payroll related functions of the payroll Xero application |
| [Object Model Documentation](https://github.com/XeroAPI/xero-php-oauth2/tree/master/doc) | Additional format of method docs, as well as full object model and field definition documentation can be found by clicking through the desired file paths |

<img src="https://i.imgur.com/7vONVOR.png" alt="drawing" width="350"/>

<hr>

## Sample Applications
Sample apps can get you started quickly with simple auth flows and advanced usage examples.

| Sample App | Description |
| --- | --- |
| [`starter-app`](https://github.com/XeroAPI/Xero-php-oauth2-starter) | Basic getting started code samples
| [`full-app`](https://github.com/XeroAPI/Xero-php-oauth2-app) | Complete app with more complex examples
| [`custom-connections-starter`](https://github.com/XeroAPI/xero-php-oauth2-custom-connections-starter) | Basic app showing Custom Connections - a Xero [premium option](https://developer.xero.com/documentation/oauth2/custom-connections) for building M2M integrations to a single orgs

<hr>

## Xero Account Requirements
- Create a [free Xero user account](https://www.xero.com/us/signup/api/)
- Login to your Xero developer [dashboard](https://developer.xero.com/app/manage) and create an API application
- Copy the credentials from your API app and store them using a secure ENV variable strategy
- Decide the [neccesary scopes](https://developer.xero.com/documentation/oauth2/scopes) for your app's functionality

# Installation
To install this SDK in your project we recommend using [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) (For OSX we recommend using [Homebrew](https://formulae.brew.sh/formula/composer)).

All third party libraries dependencies managed with Composer and the SDK requires `PHP 5.6` and later.

To install the bindings via [Composer](http://getcomposer.org/), and add the xero-php-oauth2 sdk to your `composer.json` and navigate to where your composer.json file is and run the command:
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
Xero doesn't offer support on how to use of our SDKs in different frameworks, etc. We had a recommendation by Matt @hailwood in our developer community. They integrates xero-php-oauth2 and Laravel using the following package.
* https://github.com/webfox/laravel-xero-oauth2
* https://packagist.org/packages/webfox/laravel-xero-oauth2

---
## Authentication
Below is starter code with the authorization flow. You can use the code below by creating 4 separate PHP files and securely replacing your **__CLIENT_ID__, __CLIENT_SECRET__ and __REDIRECT_URI__**

All API requests go through Xero's OAuth2.0 gateway and require a valid `access_token` to be set on the `client` which appends the `access_token` [JWT](https://jwt.io/) to the header of each request.

If you are making an API call for the first time the code below shows the auth flow using 4 separate PHP files and will work with these secure credentials replaced with your own:
* __CLIENT_ID__
* __CLIENT_SECRET__ 
* __REDIRECT_URI__

> You can also see usage of the sdk in our [sample app](https://github.com/XeroAPI/xero-php-oauth2-starter).

### Important
The RedirectURI (ex. http://localhost:8888/pathToApp/callback.php) in your code needs to point to the callback.php file and match the RedirectURI you set when creating your Xero app. 

1. Point your browser to authorization.php, you'll be redirected to Xero where you'll login and select a Xero org to authorize. We recommend the **Demo Company** org, since this code can modify data in the org you connect to.
2. Once complete, you'll be returned to your app to the redirect URI which should point to the callback.php. 
3. In callback.php, you'll obtain an access token which we'll use in authorizedResource.php to create, read, update and delete information in the connected Xero org.

### authorization.php
```php
<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';

  session_start();

  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__CLIENT_ID__',   
    'clientSecret'            => '__CLIENT_SECRET__',
    'redirectUri'             => '__REDIRECT_URI__',
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
    'clientId'                => '__CLIENT_ID__',   
    'clientSecret'            => '__CLIENT_SECRET__',
    'redirectUri'             => '__REDIRECT_URI__', 
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
      'clientId'                => '__CLIENT_ID__',
      'clientSecret'            => '__CLIENT_SECRET__',
      'redirectUri'             => 'http://localhost:8888/xero-php-oauth2-starter/callback.php',
      'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
      'urlAccessToken'          => 'https://identity.xero.com/connect/token',
      'urlResourceOwnerDetails' => 'https://identity.xero.com/resources'
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
        if ( count($apiResponse->getInvoices()) > 0 ) {
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
    } else if ($_GET["action"] == 5) {
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
---

## Configuration

The `storage.php` is a simple recommendation. In your own app you should be securely persisting the token set data in relation to the user who has authenticated the Xero API connection. Each time you want to call the Xero API you will need to access the previously generated token set, initialize it on the SDK `client`, and refresh the `access_token` prior to making API calls.

### Token Set
| key | value | description |
| --- | --- | --- |
| id_token: | "xxx.yyy.zzz" | [OpenID Connect](https://openid.net/connect/) token returned if `openid profile email` scopes accepted |
| access_token: | "xxx.yyy.zzz" | [Bearer token](https://oauth.net/2/jwt/) with a 30 minute expiration required for all API calls |
| expires_in: | 1800 | Time in seconds till the token expires - 1800s is 30m |
| refresh_token: | "XXXXXXX" | Alphanumeric string used to obtain a new Token Set w/ a fresh access_token - 60 day expiry |
| scope: | "email profile openid accounting.transactions offline_access" | The Xero permissions that are embedded in the `access_token` |

---

## Custom Connections 
Custom Connections are a Xero [premium option](https://developer.xero.com/documentation/oauth2/custom-connections) used for building M2M integrations to a single organisation. A custom connection uses OAuth2.0's [`client_credentis`](https://www.oauth.com/oauth2-servers/access-tokens/client-credentials/) grant which eliminates the step of exchanging the temporary code for a token set.

We also have a [starter application](https://github.com/XeroAPI/Xero-php-oauth2-custom-connections-starter) for more code samples of this auth flow in PHP.

To use this SDK with a Custom Connections:
```php
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__CLIENT_ID__',
    'clientSecret'            => '__CLIENT_SECRET__',
    'redirectUri'             => '__REDIRECT_URI__ ',
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
    'urlResourceOwnerDetails' => 'https://identity.xero.com/resources'
  ]);

  try {
    // Try to get an access token using the client credentials grant.
    $accessToken = $provider->getAccessToken('client_credentials');
    echo($accessToken->getToken());
  } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    // Failed to get the access token
    exit($e->getMessage());
  }
```

Because Custom Connections are only valid for a single organisation you don't need to pass the `xero-tenant-id` as the first parameter to every method, or more specifically for this SDK `xeroTenantId` can be an empty string.

---

## App Store Subscriptions

If you are implementing subscriptions to participate in Xero's App Store you will need to setup [App Store subscriptions](https://developer.xero.com/documentation/guides/how-to-guides/xero-app-store-referrals/) endpoints.

When a plan is successfully purchased, the user is redirected back to the URL specified in the setup process. The Xero App Store appends the subscription Id to this URL so you can immediately determine what plan the user has subscribed to through the subscriptions API.

With your app credentials you can create a client via `client_credentials` grant_type with the `marketplace.billing` scope. This unique access_token will allow you to query any functions in `appStoreApi`. Client Credentials tokens to query app store endpoints will only work for apps that have completed the App Store on-boarding process.

```php
// => /post-purchase-url?subscriptionId=03bc74f2-1237-4477-b782-2dfb1a6d8b21

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
  'clientId'                => '__CLIENT_ID__',
  'clientSecret'            => '__CLIENT_SECRET__',
  'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
  'urlAccessToken'          => 'https://identity.xero.com/connect/token',
  'urlResourceOwnerDetails' => 'https://identity.xero.com/resources'
]);

$apiInstance = new XeroAPI\XeroPHP\Api\AppStoreApi(
    new GuzzleHttp\Client(),
    $config
);

$accessToken = $provider->getAccessToken('client_credentials');

$apiResponse = $apiInstance->getSubscription($subscriptionId);

echo($apiResponse);
```
You should use this subscription data to provision user access/permissions to your application.

### App Store Subscription Webhooks

In additon to a subscription Id being passed through the URL, when a purchase or an upgrade takes place you will be notified via a webhook. You can then use the subscription Id in the webhook payload to query the AppStore endpoints and determine what plan the user purchased, upgraded, downgraded or cancelled.

Refer to Xero's documenation to learn more about setting up and receiving webhooks.
> https://developer.xero.com/documentation/guides/webhooks/overview/
## API Clients
You can access the different API sets and their available methods through the following API sets:
* AccountingApi
* AssetApi
* ProjectApi
* FilesApi
* PayrollAuApi
* PayrollNzApi
* PayrollUkApi
* AppStoreApi

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( 'YOUR_ACCESS_TOKEN' );       

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    new GuzzleHttp\Client(),
    $config
);
$xeroTenantId = "YOUR_XERO_TENANT_ID";

$account = new XeroAPI\XeroPHP\Models\Accounting\Account;
$account->setCode('123456');
$account->setName('FooBar');
$account->setType(XeroAPI\XeroPHP\Models\Accounting\AccountType::EXPENSE);
$account->setDescription('Hello World');

try {
  $result = $apiInstance->createAccount($xeroTenantId, $account);
} catch (Exception $e) {
  echo 'Exception when calling AccountingApi->createAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```
Or for the Assets API:
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( 'YOUR_ACCESS_TOKEN' );       

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    new GuzzleHttp\Client(),
    $config
);
$xeroTenantId = "YOUR_XERO_TENANT_ID";
$status = ;
$page = 1;
$pageSize = 5;
$orderBy = "AssetName";
$sortDirection = "ASC";
$filterBy = "Company Car";

try {
  $result = $apiInstance->getAssets($xeroTenantId, $status, $page, $pageSize, $orderBy, $sortDirection, $filterBy);
} catch (Exception $e) {
  echo 'Exception when calling AssetApi->getAssets: ', $e->getMessage(), PHP_EOL;
}
?>
```

> Full method docs can be browsed here: https://xeroapi.github.io/xero-php-oauth2/docs/v2/accounting/index.html

---
## SDK conventions

### Accessing HTTP Headers

Every function has with it a more verbose `WithHttpInfo` option in case you need to build logic around any request headers.

For example:
* `getInvoices` -> `getInvoicesWithHttpInfo`
* `getContacts` -> `getContactsWithHttpInfo`

This will return an array of 3 elements from the HTTP request. 
1. The object deserialized via the accounting object
2. The HTTP status code
3. The Response Headers

```php
return [
  AccountingObjectSerializer::deserialize($content, '\XeroAPI\XeroPHP\Models\Accounting\Organisations', []),
  $response->getStatusCode(),
  $response->getHeaders()
];
```

```php
$apiResponse = $apiInstance->getInvoicesWithHttpInfo($xeroTenantId, $if_modified_since, $where, $order, $ids, $invoice_numbers, $contact_ids, $statuses, $page,$include_archived, $created_by_my_app, $unitdp);
echo '$apiResponse: ' . json_encode($apiResponse[2]);
```

`$apiResponse: {"Content-Type":["application\/json; charset=utf-8"],"Content-Length":["2116"],"Server":["nginx"],"Xero-Correlation-Id":["9a8fb7f7-e3e6-4f66-a170-88effabe9f4e"],"X-AppMinLimit-Remaining":["9997"],"X-MinLimit-Remaining":["57"],"X-DayLimit-Remaining":["4954"],"Expires":["Fri, 23 Jul 2021 17:32:31 GMT"],"Cache-Control":["max-age=0, no-cache, no-store"],"Pragma":["no-cache"],"Date":["Fri, 23 Jul 2021 17:32:31 GMT"],"Connection":["keep-alive"],"X-Client-TLS-ver":["tls1.3"]}`

### JWT decoding and Signup with Xero

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

### Methods to access Dates in Accounting have changed since version 2.x

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

---
## Contributing
PRs, issues, and discussion are highly appreciated and encouraged. Note that the majority of this project is generated code based on [Xero's OpenAPI specs](https://github.com/XeroAPI/Xero-OpenAPI) - PR's will be evaluated and pre-merge will be incorporated into the root generation templates.


### Versioning
We do our best to keep OS industry `semver` standards, but we can make mistakes! If something is not accurately reflected in a version's release notes please let the team know.

### Participating in Xero’s developer community

This SDK is one of a number of SDK’s that the Xero Developer team builds and maintains. We are grateful for all the contributions that the community makes. 

Here are a few things you should be aware of as a contributor:
* Xero has adopted the Contributor Covenant [Code of Conduct](https://github.com/XeroAPI/xero-ruby/blob/master/CODE_OF_CONDUCT.md), we expect all contributors in our community to adhere to it
* If you raise an issue then please make sure to fill out the github issue template, doing so helps us help you 
* You’re welcome to raise PRs. As our SDKs are generated we may use your code in the core SDK build instead of merging your code
* We have a [contribution guide](https://github.com/XeroAPI/xero-ruby/blob/master/CONTRIBUTING.md) for you to follow when contributing to this SDK
* Curious about how we generate our SDK’s? Have a [read of our process](https://devblog.xero.com/building-sdks-for-the-future-b79ff726dfd6) and have a look at our [OpenAPISpec](https://github.com/XeroAPI/Xero-OpenAPI)
* This software is published under the [MIT License](https://github.com/XeroAPI/xero-ruby/blob/master/LICENSE)

For questions that aren’t related to SDKs please refer to our [developer support page](https://developer.xero.com/support/).
