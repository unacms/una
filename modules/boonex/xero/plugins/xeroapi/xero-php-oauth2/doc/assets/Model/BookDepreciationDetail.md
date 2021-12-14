# BookDepreciationDetail

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**current_capital_gain** | **double** | When an asset is disposed, this will be the sell price minus the purchase price if a profit was made. | [optional] 
**current_gain_loss** | **double** | When an asset is disposed, this will be the lowest one of sell price or purchase price, minus the current book value. | [optional] 
**depreciation_start_date** | [**\DateTime**](\DateTime.md) | YYYY-MM-DD | [optional] 
**cost_limit** | **double** | The value of the asset you want to depreciate, if this is less than the cost of the asset. | [optional] 
**residual_value** | **double** | The value of the asset remaining when you&#39;ve fully depreciated it. | [optional] 
**prior_accum_depreciation_amount** | **double** | All depreciation prior to the current financial year. | [optional] 
**current_accum_depreciation_amount** | **double** | All depreciation occurring in the current financial year. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


