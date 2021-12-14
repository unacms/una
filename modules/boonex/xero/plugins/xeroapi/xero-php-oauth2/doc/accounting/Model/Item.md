# Item

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | User defined item code (max length &#x3D; 30) | 
**inventory_asset_account_code** | **string** | The inventory asset account for the item. The account must be of type INVENTORY. The  COGSAccountCode in PurchaseDetails is also required to create a tracked item | [optional] 
**name** | **string** | The name of the item (max length &#x3D; 50) | [optional] 
**is_sold** | **bool** | Boolean value, defaults to true. When IsSold is true the item will be available on sales transactions in the Xero UI. If IsSold is updated to false then Description and SalesDetails values will be nulled. | [optional] 
**is_purchased** | **bool** | Boolean value, defaults to true. When IsPurchased is true the item is available for purchase transactions in the Xero UI. If IsPurchased is updated to false then PurchaseDescription and PurchaseDetails values will be nulled. | [optional] 
**description** | **string** | The sales description of the item (max length &#x3D; 4000) | [optional] 
**purchase_description** | **string** | The purchase description of the item (max length &#x3D; 4000) | [optional] 
**purchase_details** | [**\XeroAPI\XeroPHP\Models\Accounting\Purchase**](Purchase.md) |  | [optional] 
**sales_details** | [**\XeroAPI\XeroPHP\Models\Accounting\Purchase**](Purchase.md) |  | [optional] 
**is_tracked_as_inventory** | **bool** | True for items that are tracked as inventory. An item will be tracked as inventory if the InventoryAssetAccountCode and COGSAccountCode are set. | [optional] 
**total_cost_pool** | **double** | The value of the item on hand. Calculated using average cost accounting. | [optional] 
**quantity_on_hand** | **double** | The quantity of the item on hand | [optional] 
**updated_date_utc** | **string** | Last modified date in UTC format | [optional] 
**item_id** | **string** | The Xero identifier for an Item | [optional] 
**status_attribute_string** | **string** | Status of object | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


