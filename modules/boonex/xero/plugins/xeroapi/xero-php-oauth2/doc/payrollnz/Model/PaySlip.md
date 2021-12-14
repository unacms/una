# PaySlip

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pay_slip_id** | **string** | The Xero identifier for a PaySlip | [optional] 
**employee_id** | **string** | The Xero identifier for payroll employee | [optional] 
**pay_run_id** | **string** | The Xero identifier for the associated payrun | [optional] 
**last_edited** | [**\DateTime**](\DateTime.md) | The date payslip was last updated | [optional] 
**first_name** | **string** | Employee first name | [optional] 
**last_name** | **string** | Employee last name | [optional] 
**total_earnings** | **double** | Total earnings before any deductions. Same as gross earnings for NZ. | [optional] 
**gross_earnings** | **double** | Total earnings before any deductions. Same as total earnings for NZ. | [optional] 
**total_pay** | **double** | The employee net pay | [optional] 
**total_employer_taxes** | **double** | The employer&#39;s tax obligation | [optional] 
**total_employee_taxes** | **double** | The part of an employee&#39;s earnings that is deducted for tax purposes | [optional] 
**total_deductions** | **double** | Total amount subtracted from an employee&#39;s earnings to reach total pay | [optional] 
**total_reimbursements** | **double** | Total reimbursements are nontaxable payments to an employee used to repay out-of-pocket expenses when the person incurs those expenses through employment | [optional] 
**total_statutory_deductions** | **double** | Total amounts required by law to subtract from the employee&#39;s earnings | [optional] 
**total_superannuation** | **double** | Benefits (also called fringe benefits, perquisites or perks) are various non-earnings compensations provided to employees in addition to their normal earnings or salaries | [optional] 
**bacs_hash** | **string** | BACS Service User Number | [optional] 
**payment_method** | **string** | The payment method code | [optional] 
**earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\EarningsLine[]**](EarningsLine.md) |  | [optional] 
**leave_earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\LeaveEarningsLine[]**](LeaveEarningsLine.md) |  | [optional] 
**timesheet_earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TimesheetEarningsLine[]**](TimesheetEarningsLine.md) |  | [optional] 
**deduction_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\DeductionLine[]**](DeductionLine.md) |  | [optional] 
**reimbursement_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\ReimbursementLine[]**](ReimbursementLine.md) |  | [optional] 
**leave_accrual_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\LeaveAccrualLine[]**](LeaveAccrualLine.md) |  | [optional] 
**superannuation_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\SuperannuationLine[]**](SuperannuationLine.md) |  | [optional] 
**payment_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\PaymentLine[]**](PaymentLine.md) |  | [optional] 
**employee_tax_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TaxLine[]**](TaxLine.md) |  | [optional] 
**employer_tax_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TaxLine[]**](TaxLine.md) |  | [optional] 
**statutory_deduction_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\StatutoryDeductionLine[]**](StatutoryDeductionLine.md) |  | [optional] 
**tax_settings** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TaxSettings**](TaxSettings.md) |  | [optional] 
**gross_earnings_history** | [**\XeroAPI\XeroPHP\Models\PayrollNz\GrossEarningsHistory**](GrossEarningsHistory.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


