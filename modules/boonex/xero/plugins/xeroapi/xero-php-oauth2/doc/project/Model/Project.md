# Project

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**project_id** | **string** | Identifier of the project. | [optional] 
**contact_id** | **string** | Identifier of the contact this project was created for. | [optional] 
**name** | **string** | Name of the project. | 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Project\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**minutes_logged** | **int** | A total of minutes logged against all tasks on the Project. | [optional] 
**total_task_amount** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**total_expense_amount** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**estimate_amount** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**minutes_to_be_invoiced** | **int** | Minutes which have not been invoiced across all chargeable tasks in the project. | [optional] 
**task_amount_to_be_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**task_amount_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**expense_amount_to_be_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**expense_amount_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**project_amount_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**deposit** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**deposit_applied** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**credit_note_amount** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**deadline_utc** | [**\DateTime**](\DateTime.md) | Deadline for the project. UTC Date Time in ISO-8601 format. | [optional] 
**total_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**total_to_be_invoiced** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**estimate** | [**\XeroAPI\XeroPHP\Models\Project\Amount**](Amount.md) |  | [optional] 
**status** | [**\XeroAPI\XeroPHP\Models\Project\ProjectStatus**](ProjectStatus.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


