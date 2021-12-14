# XeroAPI\XeroPHP\AssetApi

All URIs are relative to *https://api.xero.com/assets.xro/1.0*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createAsset**](AssetApi.md#createAsset) | **POST** /Assets | adds a fixed asset
[**createAssetType**](AssetApi.md#createAssetType) | **POST** /AssetTypes | adds a fixed asset type
[**getAssetById**](AssetApi.md#getAssetById) | **GET** /Assets/{id} | Retrieves fixed asset by id
[**getAssetSettings**](AssetApi.md#getAssetSettings) | **GET** /Settings | searches fixed asset settings
[**getAssetTypes**](AssetApi.md#getAssetTypes) | **GET** /AssetTypes | searches fixed asset types
[**getAssets**](AssetApi.md#getAssets) | **GET** /Assets | searches fixed asset


# **createAsset**
> \XeroAPI\XeroPHP\Models\Asset\Asset createAsset($xero_tenant_id, $asset)

adds a fixed asset

Adds an asset to the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$asset = { "assetName":"Computer74863", "assetNumber":"123477544", "purchaseDate":"2020-01-01", "purchasePrice":100.0, "disposalPrice":23.23, "assetStatus":"Draft", "bookDepreciationSetting":{ "depreciationMethod":"StraightLine", "averagingMethod":"ActualDays", "depreciationRate":0.5, "depreciationCalculationMethod":"None" }, "bookDepreciationDetail":{ "currentCapitalGain":5.32, "currentGainLoss":3.88, "depreciationStartDate":"2020-01-02", "costLimit":100.0, "currentAccumDepreciationAmount":2.25 }, "AccountingBookValue":99.5 }; // \XeroAPI\XeroPHP\Models\Asset\Asset | Fixed asset you are creating

try {
    $result = $apiInstance->createAsset($xero_tenant_id, $asset);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->createAsset: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **asset** | [**\XeroAPI\XeroPHP\Models\Asset\Asset**](../Model/Asset.md)| Fixed asset you are creating |

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\Asset**](../Model/Asset.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createAssetType**
> \XeroAPI\XeroPHP\Models\Asset\AssetType createAssetType($xero_tenant_id, $asset_type)

adds a fixed asset type

Adds an fixed asset type to the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$asset_type = { "assetTypeName":"Machinery11004", "fixedAssetAccountId":"3d8d063a-c148-4bb8-8b3c-a5e2ad3b1e82", "depreciationExpenseAccountId":"d1602f69-f900-4616-8d34-90af393fa368", "accumulatedDepreciationAccountId":"9195cadd-8645-41e6-9f67-7bcd421defe8", "bookDepreciationSetting":{ "depreciationMethod":"DiminishingValue100", "averagingMethod":"ActualDays", "depreciationRate":0.05, "depreciationCalculationMethod":"None" } }; // \XeroAPI\XeroPHP\Models\Asset\AssetType | Asset type to add

try {
    $result = $apiInstance->createAssetType($xero_tenant_id, $asset_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->createAssetType: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **asset_type** | [**\XeroAPI\XeroPHP\Models\Asset\AssetType**](../Model/AssetType.md)| Asset type to add | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\AssetType**](../Model/AssetType.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAssetById**
> \XeroAPI\XeroPHP\Models\Asset\Asset getAssetById($xero_tenant_id, $id)

Retrieves fixed asset by id

By passing in the appropriate asset id, you can search for a specific fixed asset in the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$id = 4f7bcdcb-5ec1-4258-9558-19f662fccdfe; // string | fixed asset id for single object

try {
    $result = $apiInstance->getAssetById($xero_tenant_id, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->getAssetById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **id** | [**string**](../Model/.md)| fixed asset id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\Asset**](../Model/Asset.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAssetSettings**
> \XeroAPI\XeroPHP\Models\Asset\Setting getAssetSettings($xero_tenant_id)

searches fixed asset settings

By passing in the appropriate options, you can search for available fixed asset types in the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getAssetSettings($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->getAssetSettings: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\Setting**](../Model/Setting.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAssetTypes**
> \XeroAPI\XeroPHP\Models\Asset\AssetType[] getAssetTypes($xero_tenant_id)

searches fixed asset types

By passing in the appropriate options, you can search for available fixed asset types in the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getAssetTypes($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->getAssetTypes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\AssetType[]**](../Model/AssetType.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAssets**
> \XeroAPI\XeroPHP\Models\Asset\Assets getAssets($xero_tenant_id, $status, $page, $page_size, $order_by, $sort_direction, $filter_by)

searches fixed asset

By passing in the appropriate options, you can search for available fixed asset in the system

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AssetApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$status = new \XeroAPI\XeroPHP\Models\Asset\\XeroAPI\XeroPHP\Models\Asset\AssetStatusQueryParam(); // \XeroAPI\XeroPHP\Models\Asset\AssetStatusQueryParam | Required when retrieving a collection of assets. See Asset Status Codes
$page = 1; // int | Results are paged. This specifies which page of the results to return. The default page is 1.
$page_size = 5; // int | The number of records returned per page. By default the number of records returned is 10.
$order_by = AssetName; // string | Requests can be ordered by AssetType, AssetName, AssetNumber, PurchaseDate and PurchasePrice. If the asset status is DISPOSED it also allows DisposalDate and DisposalPrice.
$sort_direction = ASC; // string | ASC or DESC
$filter_by = Company Car; // string | A string that can be used to filter the list to only return assets containing the text. Checks it against the AssetName, AssetNumber, Description and AssetTypeName fields.

try {
    $result = $apiInstance->getAssets($xero_tenant_id, $status, $page, $page_size, $order_by, $sort_direction, $filter_by);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AssetApi->getAssets: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **status** | [**\XeroAPI\XeroPHP\Models\Asset\AssetStatusQueryParam**](../Model/.md)| Required when retrieving a collection of assets. See Asset Status Codes |
 **page** | **int**| Results are paged. This specifies which page of the results to return. The default page is 1. | [optional]
 **page_size** | **int**| The number of records returned per page. By default the number of records returned is 10. | [optional]
 **order_by** | **string**| Requests can be ordered by AssetType, AssetName, AssetNumber, PurchaseDate and PurchasePrice. If the asset status is DISPOSED it also allows DisposalDate and DisposalPrice. | [optional]
 **sort_direction** | **string**| ASC or DESC | [optional]
 **filter_by** | **string**| A string that can be used to filter the list to only return assets containing the text. Checks it against the AssetName, AssetNumber, Description and AssetTypeName fields. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Asset\Assets**](../Model/Assets.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

