# Payslip

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**pay_slip_id** | **string** | The Xero identifier for a Payslip | [optional] 
**employee_id** | **string** | The Xero identifier for payroll employee | [optional] 
**pay_run_id** | **string** | The Xero identifier for the associated payrun | [optional] 
**last_edited** | [**\DateTime**](\DateTime.md) | The date payslip was last updated | [optional] 
**first_name** | **string** | Employee first name | [optional] 
**last_name** | **string** | Employee last name | [optional] 
**total_earnings** | **double** | Total earnings before any deductions. Same as gross earnings for UK. | [optional] 
**gross_earnings** | **double** | Total earnings before any deductions. Same as total earnings for UK. | [optional] 
**total_pay** | **double** | The employee net pay | [optional] 
**total_employer_taxes** | **double** | The employer&#39;s tax obligation | [optional] 
**total_employee_taxes** | **double** | The part of an employee&#39;s earnings that is deducted for tax purposes | [optional] 
**total_deductions** | **double** | Total amount subtracted from an employee&#39;s earnings to reach total pay | [optional] 
**total_reimbursements** | **double** | Total reimbursements are nontaxable payments to an employee used to repay out-of-pocket expenses when the person incurs those expenses through employment | [optional] 
**total_court_orders** | **double** | Total amounts required by law to subtract from the employee&#39;s earnings | [optional] 
**total_benefits** | **double** | Benefits (also called fringe benefits, perquisites or perks) are various non-earnings compensations provided to employees in addition to their normal earnings or salaries | [optional] 
**bacs_hash** | **string** | BACS Service User Number | [optional] 
**payment_method** | **string** | The payment method code | [optional] 
**earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsLine[]**](EarningsLine.md) |  | [optional] 
**leave_earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveEarningsLine[]**](LeaveEarningsLine.md) |  | [optional] 
**timesheet_earnings_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetEarningsLine[]**](TimesheetEarningsLine.md) |  | [optional] 
**deduction_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\DeductionLine[]**](DeductionLine.md) |  | [optional] 
**reimbursement_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\ReimbursementLine[]**](ReimbursementLine.md) |  | [optional] 
**leave_accrual_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveAccrualLine[]**](LeaveAccrualLine.md) |  | [optional] 
**benefit_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\BenefitLine[]**](BenefitLine.md) |  | [optional] 
**payment_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\PaymentLine[]**](PaymentLine.md) |  | [optional] 
**employee_tax_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\TaxLine[]**](TaxLine.md) |  | [optional] 
**employer_tax_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\TaxLine[]**](TaxLine.md) |  | [optional] 
**court_order_lines** | [**\XeroAPI\XeroPHP\Models\PayrollUk\CourtOrderLine[]**](CourtOrderLine.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


