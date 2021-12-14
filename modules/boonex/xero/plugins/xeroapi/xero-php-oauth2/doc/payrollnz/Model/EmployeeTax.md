# EmployeeTax

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ird_number** | **string** | The IRD Number. | [optional] 
**tax_code** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TaxCode**](TaxCode.md) |  | [optional] 
**special_tax_rate_percentage** | **double** | Special tax rate percentage. | [optional] 
**has_special_student_loan_rate** | **bool** | Does the employee has a special student loan rate? | [optional] 
**special_student_loan_rate_percentage** | **double** | The employee student loan rate percentage. | [optional] 
**is_eligible_for_kiwi_saver** | **bool** | The employee eligibility for KiwiSaver. | [optional] 
**esct_rate_percentage** | **double** | Employer superannuation contribution tax rate. | [optional] 
**kiwi_saver_contributions** | **string** | Contribution Option which can be &#39;MakeContributions&#39; &#39;OptOut&#39;, &#39;OnAContributionsHoliday&#39;, &#39;OnASavingsSuspension&#39;, &#39;NotCurrentlyAKiwiSaverMember&#39; for employees without a KiwiSaver membership | [optional] 
**kiwi_saver_employee_contribution_rate_percentage** | **double** | Employee Contribution percentage. | [optional] 
**kiwi_saver_employer_contribution_rate_percentage** | **double** | Employer Contribution percentage. | [optional] 
**kiwi_saver_employer_salary_sacrifice_contribution_rate_percentage** | **double** | Employer Contribution through Salary Sacrifice percentage. | [optional] 
**kiwi_saver_opt_out_date** | [**\DateTime**](\DateTime.md) | Opt Out Date. | [optional] 
**kiwi_saver_contribution_holiday_end_date** | [**\DateTime**](\DateTime.md) | Contribution holiday expiry date or end date. | [optional] 
**has_student_loan_balance** | **bool** | Does the employee have a remaining student loan balance? Set a remaining balance if you have received a letter from IR. | [optional] 
**student_loan_balance** | **double** | The employee&#39;s student loan balance shown on the letter from IR. | [optional] 
**student_loan_as_at** | [**\DateTime**](\DateTime.md) | The date of the letter from IR. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


