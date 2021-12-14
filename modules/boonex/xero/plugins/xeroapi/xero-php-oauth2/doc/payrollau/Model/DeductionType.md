# DeductionType

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** | Name of the earnings rate (max length &#x3D; 100) | [optional] 
**account_code** | **string** | See Accounts | [optional] 
**reduces_tax** | **bool** | Indicates that this is a pre-tax deduction that will reduce the amount of tax you withhold from an employee. | [optional] 
**reduces_super** | **bool** | Most deductions don’t reduce your superannuation guarantee contribution liability, so typically you will not set any value for this. | [optional] 
**is_exempt_from_w1** | **bool** | Boolean to determine if the deduction type is reportable or exempt from W1 | [optional] 
**deduction_type_id** | **string** | Xero identifier | [optional] 
**updated_date_utc** | **string** | Last modified timestamp | [optional] 
**deduction_category** | **string** |  | [optional] 
**current_record** | **bool** | Is the current record | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


