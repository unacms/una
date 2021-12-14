# Asset

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**asset_id** | **string** | The Xero-generated Id for the asset | [optional] 
**asset_name** | **string** | The name of the asset | 
**asset_type_id** | **string** | The Xero-generated Id for the asset type | [optional] 
**asset_number** | **string** | Must be unique. | [optional] 
**purchase_date** | [**\DateTime**](\DateTime.md) | The date the asset was purchased YYYY-MM-DD | [optional] 
**purchase_price** | **double** | The purchase price of the asset | [optional] 
**disposal_date** | [**\DateTime**](\DateTime.md) | The date the asset was disposed | [optional] 
**disposal_price** | **double** | The price the asset was disposed at | [optional] 
**asset_status** | [**\XeroAPI\XeroPHP\Models\Asset\AssetStatus**](AssetStatus.md) |  | [optional] 
**warranty_expiry_date** | **string** | The date the asset’s warranty expires (if needed) YYYY-MM-DD | [optional] 
**serial_number** | **string** | The asset&#39;s serial number | [optional] 
**book_depreciation_setting** | [**\XeroAPI\XeroPHP\Models\Asset\BookDepreciationSetting**](BookDepreciationSetting.md) |  | [optional] 
**book_depreciation_detail** | [**\XeroAPI\XeroPHP\Models\Asset\BookDepreciationDetail**](BookDepreciationDetail.md) |  | [optional] 
**can_rollback** | **bool** | Boolean to indicate whether depreciation can be rolled back for this asset individually. This is true if it doesn&#39;t have &#39;legacy&#39; journal entries and if there is no lock period that would prevent this asset from rolling back. | [optional] 
**accounting_book_value** | **double** | The accounting value of the asset | [optional] 
**is_delete_enabled_for_date** | **bool** | Boolean to indicate whether delete is enabled | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


