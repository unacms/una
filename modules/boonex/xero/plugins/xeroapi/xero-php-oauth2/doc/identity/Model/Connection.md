# Connection

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** | Xero identifier | [optional] 
**tenant_id** | **string** | Xero identifier of organisation | [optional] 
**auth_event_id** | **string** | Identifier shared across connections authorised at the same time | [optional] 
**tenant_type** | **string** | Xero tenant type (i.e. ORGANISATION, PRACTICE) | [optional] 
**tenant_name** | **string** | Xero tenant name | [optional] 
**created_date_utc** | [**\DateTime**](\DateTime.md) | The date when the user connected this tenant to your app | [optional] 
**updated_date_utc** | [**\DateTime**](\DateTime.md) | The date when the user most recently connected this tenant to your app. May differ to the created date if the user has disconnected and subsequently reconnected this tenant to your app. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


