# EarningsRate

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**earnings_rate_id** | **string** | Xero unique identifier for an earning rate | [optional] 
**name** | **string** | Name of the earning rate | 
**earnings_type** | **string** | Indicates how an employee will be paid when taking this type of earning | 
**rate_type** | **string** | Indicates the type of the earning rate | 
**type_of_units** | **string** | The type of units used to record earnings | 
**current_record** | **bool** | Indicates whether an earning type is active | [optional] 
**expense_account_id** | **string** | The account that will be used for the earnings rate | 
**rate_per_unit** | **double** | Default rate per unit (optional). Only applicable if RateType is RatePerUnit | [optional] 
**multiple_of_ordinary_earnings_rate** | **double** | This is the multiplier used to calculate the rate per unit, based on the employee’s ordinary earnings rate. For example, for time and a half enter 1.5. Only applicable if RateType is MultipleOfOrdinaryEarningsRate | [optional] 
**fixed_amount** | **double** | Optional Fixed Rate Amount. Applicable for FixedAmount Rate | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


