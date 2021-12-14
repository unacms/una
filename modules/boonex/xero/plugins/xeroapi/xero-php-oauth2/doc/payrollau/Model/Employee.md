# Employee

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**first_name** | **string** | First name of employee | 
**last_name** | **string** | Last name of employee | 
**date_of_birth** | **string** | Date of birth of the employee (YYYY-MM-DD) | 
**home_address** | [**\XeroAPI\XeroPHP\Models\PayrollAu\HomeAddress**](HomeAddress.md) |  | [optional] 
**start_date** | **string** | Start date for an employee (YYYY-MM-DD) | [optional] 
**title** | **string** | Title of the employee | [optional] 
**middle_names** | **string** | Middle name(s) of the employee | [optional] 
**email** | **string** | The email address for the employee | [optional] 
**gender** | **string** | The employee’s gender. See Employee Gender | [optional] 
**phone** | **string** | Employee phone number | [optional] 
**mobile** | **string** | Employee mobile number | [optional] 
**twitter_user_name** | **string** | Employee’s twitter name | [optional] 
**is_authorised_to_approve_leave** | **bool** | Authorised to approve other employees&#39; leave requests | [optional] 
**is_authorised_to_approve_timesheets** | **bool** | Authorised to approve timesheets | [optional] 
**job_title** | **string** | JobTitle of the employee | [optional] 
**classification** | **string** | Employees classification | [optional] 
**ordinary_earnings_rate_id** | **string** | Xero unique identifier for earnings rate | [optional] 
**payroll_calendar_id** | **string** | Xero unique identifier for payroll calendar for the employee | [optional] 
**employee_group_name** | **string** | The Employee Group allows you to report on payroll expenses and liabilities for each group of employees | [optional] 
**employee_id** | **string** | Xero unique identifier for an Employee | [optional] 
**termination_date** | **string** | Employee Termination Date (YYYY-MM-DD) | [optional] 
**bank_accounts** | [**\XeroAPI\XeroPHP\Models\PayrollAu\BankAccount[]**](BankAccount.md) |  | [optional] 
**pay_template** | [**\XeroAPI\XeroPHP\Models\PayrollAu\PayTemplate**](PayTemplate.md) |  | [optional] 
**opening_balances** | [**\XeroAPI\XeroPHP\Models\PayrollAu\OpeningBalances**](OpeningBalances.md) |  | [optional] 
**tax_declaration** | [**\XeroAPI\XeroPHP\Models\PayrollAu\TaxDeclaration**](TaxDeclaration.md) |  | [optional] 
**leave_balances** | [**\XeroAPI\XeroPHP\Models\PayrollAu\LeaveBalance[]**](LeaveBalance.md) |  | [optional] 
**leave_lines** | [**\XeroAPI\XeroPHP\Models\PayrollAu\LeaveLine[]**](LeaveLine.md) |  | [optional] 
**super_memberships** | [**\XeroAPI\XeroPHP\Models\PayrollAu\SuperMembership[]**](SuperMembership.md) |  | [optional] 
**status** | [**\XeroAPI\XeroPHP\Models\PayrollAu\EmployeeStatus**](EmployeeStatus.md) |  | [optional] 
**updated_date_utc** | **string** | Last modified timestamp | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\PayrollAu\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


