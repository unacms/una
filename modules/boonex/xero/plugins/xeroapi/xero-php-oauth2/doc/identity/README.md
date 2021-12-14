# xero-php-oauth2

## Identity API Documentation

Please follow the [README instructions](https://github.com/XeroAPI/xero-php-oauth2/blob/master/README.md) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

  // Init your oAuth2 provider
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__YOUR_CLIENT_ID__',   
    'clientSecret'            => '__YOUR_CLIENT_SECRET__',
    'redirectUri'             => '__YOUR_REDIRECT_URI__',  //same as at developer.xero.com/myapps
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token'
  ]);


  // Configure OAuth2 access token for authorization: OAuth2
  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');     

  $identityApi = new XeroAPI\XeroPHP\Api\IdentityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  $connections = $identityApi->getConnections();
  $id = $connections[0]->getId();
  	
  try {
    $result = $identityApi->deleteConnection($id);
    print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling identityApi->deleteConnection: ', $e->getMessage(), PHP_EOL;
  }

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*IdentityApi* | [**deleteConnection**](docs/Api/IdentityApi.md#deleteconnection) | **DELETE** /connections/{id} | Allows you to delete a connection for this user (i.e. disconnect a tenant)
*IdentityApi* | [**getConnections**](docs/Api/IdentityApi.md#getconnections) | **GET** /connections | Allows you to retrieve the connections for this user


## Documentation For Models

 - [AccessToken](docs/Model/AccessToken.md)
 - [Connection](docs/Model/Connection.md)
 - [RefreshToken](docs/Model/RefreshToken.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **email**: Grant read-only access to your email
 - **openid**: Grant read-only access to your open id
 - **profile**: your profile information
 - **accounting.transactions**: Grant read-write access to bank transactions, credit notes, invoices, repeating invoices
 - **accounting.transactions.read**: Grant read-only access to invoices
 - **accounting.reports.read**: Grant read-only access to accounting reports
 - **accounting.journals.read**: Grant read-only access to journals
 - **accounting.settings**: Grant read-write access to organisation and account settings
 - **accounting.settings.read**: Grant read-only access to organisation and account settings
 - **accounting.contacts**: Grant read-write access to
 - **accounting.contacts.read**: Grant read-only access to
 - **accounting.attachments**: Grant read-write access to
 - **accounting.attachments.read**: Grant read-only access to
 - **assets assets.read**: Grant read-only access to
 - **files**: Grant read-write access to
 - **files.read**: Grant read-only access to
 - **payroll**: Grant read-write access to
 - **payroll.read**: Grant read-only access to
 - **payroll.employees**: Grant read-write access to
 - **payroll.employees.read**: Grant read-only access to
 - **payroll.leaveapplications**: Grant read-write access to
 - **payroll.leaveapplications.read**: Grant read-only access to
 - **payroll.payitems**: Grant read-write access to
 - **payroll.payitems.read**: Grant read-only access to
 - **payroll.payrollcalendars**: Grant read-write access to
 - **payroll.payrollcalendars.read**: Grant read-only access to
 - **payroll.payruns**: Grant read-write access to
 - **payroll.payruns.read**: Grant read-only access to
 - **payroll.payslip**: Grant read-write access to
 - **payroll.payslip.read**: Grant read-only access to
 - **payroll.settings.read**: Grant read-only access to
 - **payroll.superfunds**: Grant read-write access to
 - **payroll.superfunds.read**: Grant read-only access to
 - **payroll.superfundproducts.read**: Grant read-only access to
 - **payroll.timesheets**: Grant read-write access to
 - **payroll.timesheets.read**: Grant read-only access to
 - **projects**: Grant read-write access to projects
 - **projects.read**: Grant read-only access to projects


## Author

api@xero.com


