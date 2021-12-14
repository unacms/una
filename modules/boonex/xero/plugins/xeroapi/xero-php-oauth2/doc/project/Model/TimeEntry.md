# TimeEntry

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**time_entry_id** | **string** | Identifier of the time entry. | [optional] 
**user_id** | **string** | The xero user identifier of the person who logged time. | [optional] 
**project_id** | **string** | Identifier of the project, that the task (which the time entry is logged against) belongs to. | [optional] 
**task_id** | **string** | Identifier of the task that time entry is logged against. | [optional] 
**date_utc** | [**\DateTime**](\DateTime.md) | The date time that time entry is logged on. UTC Date Time in ISO-8601 format. | [optional] 
**date_entered_utc** | [**\DateTime**](\DateTime.md) | The date time that time entry is created. UTC Date Time in ISO-8601 format. By default it is set to server time. | [optional] 
**duration** | **int** | The duration of logged minutes. | [optional] 
**description** | **string** | A description of the time entry. | [optional] 
**status** | **string** | Status of the time entry. By default a time entry is created with status of &#x60;ACTIVE&#x60;. A &#x60;LOCKED&#x60; state indicates that the time entry is currently changing state (for example being invoiced). Updates are not allowed when in this state. It will have a status of INVOICED once it is invoiced. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


