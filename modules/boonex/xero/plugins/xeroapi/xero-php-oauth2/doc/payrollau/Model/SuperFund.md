# SuperFund

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**super_fund_id** | **string** | Xero identifier for a super fund | [optional] 
**type** | [**\XeroAPI\XeroPHP\Models\PayrollAu\SuperFundType**](SuperFundType.md) |  | 
**name** | **string** | Name of the super fund | [optional] 
**abn** | **string** | ABN of the self managed super fund | [optional] 
**bsb** | **string** | BSB of the self managed super fund | [optional] 
**account_number** | **string** | The account number for the self managed super fund. | [optional] 
**account_name** | **string** | The account name for the self managed super fund. | [optional] 
**electronic_service_address** | **string** | The electronic service address for the self managed super fund. | [optional] 
**employer_number** | **string** | Some funds assign a unique number to each employer | [optional] 
**spin** | **string** | The SPIN of the Regulated SuperFund. This field has been deprecated. It will only be present for legacy superfunds. New superfunds will not have a SPIN value. The USI field should be used instead of SPIN. | [optional] 
**usi** | **string** | The USI of the Regulated SuperFund | [optional] 
**updated_date_utc** | **string** | Last modified timestamp | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\PayrollAu\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


