# TimeEntryCreateOrUpdate

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**user_id** | **string** | The xero user identifier of the person logging the time. | 
**task_id** | **string** | Identifier of the task that time entry is logged against. | 
**date_utc** | [**\DateTime**](\DateTime.md) | Date time entry is logged on. UTC Date Time in ISO-8601 format. | 
**duration** | **int** | Number of minutes to be logged. Duration is between 1 and 59940 inclusively. | 
**description** | **string** | An optional description of the time entry, will be set to null if not provided during update. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


