# XeroAPI\XeroPHP\IdentityApi

All URIs are relative to *https://api.xero.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**deleteConnection**](IdentityApi.md#deleteConnection) | **DELETE** /Connections/{id} | Deletes a connection for this user (i.e. disconnect a tenant)
[**getConnections**](IdentityApi.md#getConnections) | **GET** /Connections | Retrieves the connections for this user


# **deleteConnection**
> deleteConnection($id)

Deletes a connection for this user (i.e. disconnect a tenant)

Override the base server url that include version

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\IdentityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string | Unique identifier for retrieving single object

try {
    $apiInstance->deleteConnection($id);
} catch (Exception $e) {
    echo 'Exception when calling IdentityApi->deleteConnection: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **id** | [**string**](../Model/.md)| Unique identifier for retrieving single object |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getConnections**
> \XeroAPI\XeroPHP\Models\Identity\Connection[] getConnections($auth_event_id)

Retrieves the connections for this user

Override the base server url that include version

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\IdentityApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$auth_event_id = 00000000-0000-0000-0000-000000000000; // string | Filter by authEventId

try {
    $result = $apiInstance->getConnections($auth_event_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling IdentityApi->getConnections: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **auth_event_id** | [**string**](../Model/.md)| Filter by authEventId | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Identity\Connection[]**](../Model/Connection.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

