# xero-php-oauth2

## Payroll (UK) API Documentation

Please follow the [README instructions](https://github.com/XeroAPI/xero-php-oauth2/blob/master/README.md) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

  // Init your oAuth2 provider
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__YOUR_CLIENT_ID__',   
    'clientSecret'            => '__YOUR_CLIENT_SECRET__',
    'redirectUri'             => '__YOUR_REDIRECT_URI__',  //same as at developer.xero.com/myapps
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token'
  ]);


  // Configure OAuth2 access token for authorization: OAuth2
  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');     

  $payrollUkApi = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  // \XeroAPI\XeroPHP\Models\Accounting\Employee | Request of type Employee
  $employee = new XeroAPI\XeroPHP\Models\PayrollUk\Employee;
  $employee->setFirstName("Fred");
  $employee->setLastName("Potter");
  $employee->setEmail("albus@hogwarts.edu");
  $dateOfBirth = DateTime::createFromFormat('m/d/Y', '05/29/2000');
  $employee->setDateOfBirthAsDate($dateOfBirth);

  $address = new XeroAPI\XeroPHP\Models\PayrollUk\HomeAddress;
  $address->setAddressLine1("101 Green St");
  $address->setCity("London");
  $address->setCountry("United Kingdom");
  $address->setPostalCode("6023");
  $employee->setHomeAddress($address);

  $newEmployees = [];		
  array_push($newEmployees, $employee);  

  try {
      $result = $payrollUkApi->createEmployee($xero_tenant_id, $newEmployees);
      print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling payrollUkApi->createEmployee: ', $e->getMessage(), PHP_EOL;
  }

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com/payroll.xro/2.0*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*PayrollUkApi* | [**approveTimesheet**](Api/PayrollUkApi.md#approvetimesheet) | **POST** /Timesheets/{TimesheetID}/Approve | approve a timesheet
*PayrollUkApi* | [**createBenefit**](Api/PayrollUkApi.md#createbenefit) | **POST** /Benefits | create a new benefit
*PayrollUkApi* | [**createDeduction**](Api/PayrollUkApi.md#creatededuction) | **POST** /Deductions | create a new deduction
*PayrollUkApi* | [**createEarningsRate**](Api/PayrollUkApi.md#createearningsrate) | **POST** /EarningsRates | create a new earnings rate
*PayrollUkApi* | [**createEmployee**](Api/PayrollUkApi.md#createemployee) | **POST** /Employees | creates employees
*PayrollUkApi* | [**createEmployeeEarningsTemplate**](Api/PayrollUkApi.md#createemployeeearningstemplate) | **POST** /Employees/{EmployeeId}/PayTemplates/earnings | creates employee earnings template records
*PayrollUkApi* | [**createEmployeeLeave**](Api/PayrollUkApi.md#createemployeeleave) | **POST** /Employees/{EmployeeId}/Leave | creates employee leave records
*PayrollUkApi* | [**createEmployeeLeaveType**](Api/PayrollUkApi.md#createemployeeleavetype) | **POST** /Employees/{EmployeeId}/LeaveTypes | creates employee leave type records
*PayrollUkApi* | [**createEmployeeOpeningBalances**](Api/PayrollUkApi.md#createemployeeopeningbalances) | **POST** /Employees/{EmployeeId}/ukopeningbalances | creates employee opening balances
*PayrollUkApi* | [**createEmployeePaymentMethod**](Api/PayrollUkApi.md#createemployeepaymentmethod) | **POST** /Employees/{EmployeeId}/PaymentMethods | creates employee payment method
*PayrollUkApi* | [**createEmployeeSalaryAndWage**](Api/PayrollUkApi.md#createemployeesalaryandwage) | **POST** /Employees/{EmployeeId}/SalaryAndWages | creates employee salary and wage record
*PayrollUkApi* | [**createEmployeeStatutorySickLeave**](Api/PayrollUkApi.md#createemployeestatutorysickleave) | **POST** /StatutoryLeaves/Sick | creates employee statutory sick leave records
*PayrollUkApi* | [**createEmployment**](Api/PayrollUkApi.md#createemployment) | **POST** /Employees/{EmployeeId}/Employment | creates employment
*PayrollUkApi* | [**createLeaveType**](Api/PayrollUkApi.md#createleavetype) | **POST** /LeaveTypes | create a new leave type
*PayrollUkApi* | [**createMultipleEmployeeEarningsTemplate**](Api/PayrollUkApi.md#createmultipleemployeeearningstemplate) | **POST** /Employees/{EmployeeId}/paytemplateearnings | creates multiple employee earnings template records
*PayrollUkApi* | [**createPayRunCalendar**](Api/PayrollUkApi.md#createpayruncalendar) | **POST** /PayRunCalendars | create a new payrun calendar
*PayrollUkApi* | [**createReimbursement**](Api/PayrollUkApi.md#createreimbursement) | **POST** /Reimbursements | create a new reimbursement
*PayrollUkApi* | [**createTimesheet**](Api/PayrollUkApi.md#createtimesheet) | **POST** /Timesheets | create a new timesheet
*PayrollUkApi* | [**createTimesheetLine**](Api/PayrollUkApi.md#createtimesheetline) | **POST** /Timesheets/{TimesheetID}/Lines | create a new timesheet line
*PayrollUkApi* | [**deleteEmployeeEarningsTemplate**](Api/PayrollUkApi.md#deleteemployeeearningstemplate) | **DELETE** /Employees/{EmployeeId}/PayTemplates/earnings/{PayTemplateEarningID} | deletes an employee earnings template record
*PayrollUkApi* | [**deleteEmployeeLeave**](Api/PayrollUkApi.md#deleteemployeeleave) | **DELETE** /Employees/{EmployeeId}/Leave/{LeaveID} | deletes an employee leave record
*PayrollUkApi* | [**deleteEmployeeSalaryAndWage**](Api/PayrollUkApi.md#deleteemployeesalaryandwage) | **DELETE** /Employees/{EmployeeId}/SalaryAndWages/{SalaryAndWagesID} | deletes an employee salary and wages record
*PayrollUkApi* | [**deleteTimesheet**](Api/PayrollUkApi.md#deletetimesheet) | **DELETE** /Timesheets/{TimesheetID} | delete a timesheet
*PayrollUkApi* | [**deleteTimesheetLine**](Api/PayrollUkApi.md#deletetimesheetline) | **DELETE** /Timesheets/{TimesheetID}/Lines/{TimesheetLineID} | delete a timesheet line
*PayrollUkApi* | [**getBenefit**](Api/PayrollUkApi.md#getbenefit) | **GET** /Benefits/{id} | retrieve a single benefit by id
*PayrollUkApi* | [**getBenefits**](Api/PayrollUkApi.md#getbenefits) | **GET** /Benefits | searches benefits
*PayrollUkApi* | [**getDeduction**](Api/PayrollUkApi.md#getdeduction) | **GET** /Deductions/{deductionId} | retrieve a single deduction by id
*PayrollUkApi* | [**getDeductions**](Api/PayrollUkApi.md#getdeductions) | **GET** /Deductions | searches deductions
*PayrollUkApi* | [**getEarningsOrder**](Api/PayrollUkApi.md#getearningsorder) | **GET** /EarningsOrders/{id} | retrieve a single deduction by id
*PayrollUkApi* | [**getEarningsOrders**](Api/PayrollUkApi.md#getearningsorders) | **GET** /EarningsOrders | searches earnings orders
*PayrollUkApi* | [**getEarningsRate**](Api/PayrollUkApi.md#getearningsrate) | **GET** /EarningsRates/{EarningsRateID} | retrieve a single earnings rates by id
*PayrollUkApi* | [**getEarningsRates**](Api/PayrollUkApi.md#getearningsrates) | **GET** /EarningsRates | searches earnings rates
*PayrollUkApi* | [**getEmployee**](Api/PayrollUkApi.md#getemployee) | **GET** /Employees/{EmployeeId} | searches employees
*PayrollUkApi* | [**getEmployeeLeave**](Api/PayrollUkApi.md#getemployeeleave) | **GET** /Employees/{EmployeeId}/Leave/{LeaveID} | retrieve a single employee leave record
*PayrollUkApi* | [**getEmployeeLeaveBalances**](Api/PayrollUkApi.md#getemployeeleavebalances) | **GET** /Employees/{EmployeeId}/LeaveBalances | search employee leave balances
*PayrollUkApi* | [**getEmployeeLeavePeriods**](Api/PayrollUkApi.md#getemployeeleaveperiods) | **GET** /Employees/{EmployeeId}/LeavePeriods | searches employee leave periods
*PayrollUkApi* | [**getEmployeeLeaveTypes**](Api/PayrollUkApi.md#getemployeeleavetypes) | **GET** /Employees/{EmployeeId}/LeaveTypes | searches employee leave types
*PayrollUkApi* | [**getEmployeeLeaves**](Api/PayrollUkApi.md#getemployeeleaves) | **GET** /Employees/{EmployeeId}/Leave | search employee leave records
*PayrollUkApi* | [**getEmployeeOpeningBalances**](Api/PayrollUkApi.md#getemployeeopeningbalances) | **GET** /Employees/{EmployeeId}/ukopeningbalances | retrieve employee openingbalances
*PayrollUkApi* | [**getEmployeePayTemplate**](Api/PayrollUkApi.md#getemployeepaytemplate) | **GET** /Employees/{EmployeeId}/PayTemplates | searches employee pay templates
*PayrollUkApi* | [**getEmployeePaymentMethod**](Api/PayrollUkApi.md#getemployeepaymentmethod) | **GET** /Employees/{EmployeeId}/PaymentMethods | retrieves an employee&#39;s payment method
*PayrollUkApi* | [**getEmployeeSalaryAndWage**](Api/PayrollUkApi.md#getemployeesalaryandwage) | **GET** /Employees/{EmployeeId}/SalaryAndWages/{SalaryAndWagesID} | get employee salary and wages record by id
*PayrollUkApi* | [**getEmployeeSalaryAndWages**](Api/PayrollUkApi.md#getemployeesalaryandwages) | **GET** /Employees/{EmployeeId}/SalaryAndWages | retrieves an employee&#39;s salary and wages
*PayrollUkApi* | [**getEmployeeStatutoryLeaveBalances**](Api/PayrollUkApi.md#getemployeestatutoryleavebalances) | **GET** /Employees/{EmployeeId}/StatutoryLeaveBalance | search employee leave balances
*PayrollUkApi* | [**getEmployeeStatutorySickLeave**](Api/PayrollUkApi.md#getemployeestatutorysickleave) | **GET** /StatutoryLeaves/Sick/{StatutorySickLeaveID} | retrieve a statutory sick leave for an employee
*PayrollUkApi* | [**getEmployeeTax**](Api/PayrollUkApi.md#getemployeetax) | **GET** /Employees/{EmployeeId}/Tax | searches tax records for an employee
*PayrollUkApi* | [**getEmployees**](Api/PayrollUkApi.md#getemployees) | **GET** /Employees | searches employees
*PayrollUkApi* | [**getLeaveType**](Api/PayrollUkApi.md#getleavetype) | **GET** /LeaveTypes/{LeaveTypeID} | retrieve a single leave type by id
*PayrollUkApi* | [**getLeaveTypes**](Api/PayrollUkApi.md#getleavetypes) | **GET** /LeaveTypes | searches leave types
*PayrollUkApi* | [**getPayRun**](Api/PayrollUkApi.md#getpayrun) | **GET** /PayRuns/{PayRunID} | retrieve a single pay run by id
*PayrollUkApi* | [**getPayRunCalendar**](Api/PayrollUkApi.md#getpayruncalendar) | **GET** /PayRunCalendars/{PayRunCalendarID} | retrieve a single payrun calendar by id
*PayrollUkApi* | [**getPayRunCalendars**](Api/PayrollUkApi.md#getpayruncalendars) | **GET** /PayRunCalendars | searches payrun calendars
*PayrollUkApi* | [**getPayRuns**](Api/PayrollUkApi.md#getpayruns) | **GET** /PayRuns | searches pay runs
*PayrollUkApi* | [**getPaySlip**](Api/PayrollUkApi.md#getpayslip) | **GET** /Payslips/{PayslipID} | retrieve a single payslip by id
*PayrollUkApi* | [**getPaySlips**](Api/PayrollUkApi.md#getpayslips) | **GET** /Payslips | searches payslips
*PayrollUkApi* | [**getReimbursement**](Api/PayrollUkApi.md#getreimbursement) | **GET** /Reimbursements/{ReimbursementID} | retrieve a single reimbursement by id
*PayrollUkApi* | [**getReimbursements**](Api/PayrollUkApi.md#getreimbursements) | **GET** /Reimbursements | searches reimbursements
*PayrollUkApi* | [**getSettings**](Api/PayrollUkApi.md#getsettings) | **GET** /Settings | searches settings
*PayrollUkApi* | [**getStatutoryLeaveSummary**](Api/PayrollUkApi.md#getstatutoryleavesummary) | **GET** /statutoryleaves/summary/{EmployeeId} | retrieve a summary of statutory leaves for an employee
*PayrollUkApi* | [**getTimesheet**](Api/PayrollUkApi.md#gettimesheet) | **GET** /Timesheets/{TimesheetID} | retrieve a single timesheet by id
*PayrollUkApi* | [**getTimesheets**](Api/PayrollUkApi.md#gettimesheets) | **GET** /Timesheets | searches timesheets
*PayrollUkApi* | [**getTrackingCategories**](Api/PayrollUkApi.md#gettrackingcategories) | **GET** /settings/trackingCategories | searches tracking categories
*PayrollUkApi* | [**revertTimesheet**](Api/PayrollUkApi.md#reverttimesheet) | **POST** /Timesheets/{TimesheetID}/RevertToDraft | revert a timesheet to draft
*PayrollUkApi* | [**updateEmployee**](Api/PayrollUkApi.md#updateemployee) | **PUT** /Employees/{EmployeeId} | updates employee
*PayrollUkApi* | [**updateEmployeeEarningsTemplate**](Api/PayrollUkApi.md#updateemployeeearningstemplate) | **PUT** /Employees/{EmployeeId}/PayTemplates/earnings/{PayTemplateEarningID} | updates employee earnings template records
*PayrollUkApi* | [**updateEmployeeLeave**](Api/PayrollUkApi.md#updateemployeeleave) | **PUT** /Employees/{EmployeeId}/Leave/{LeaveID} | updates employee leave records
*PayrollUkApi* | [**updateEmployeeOpeningBalances**](Api/PayrollUkApi.md#updateemployeeopeningbalances) | **PUT** /Employees/{EmployeeId}/ukopeningbalances | updates employee opening balances
*PayrollUkApi* | [**updateEmployeeSalaryAndWage**](Api/PayrollUkApi.md#updateemployeesalaryandwage) | **PUT** /Employees/{EmployeeId}/SalaryAndWages/{SalaryAndWagesID} | updates employee salary and wages record
*PayrollUkApi* | [**updatePayRun**](Api/PayrollUkApi.md#updatepayrun) | **PUT** /PayRuns/{PayRunID} | update a pay run
*PayrollUkApi* | [**updateTimesheetLine**](Api/PayrollUkApi.md#updatetimesheetline) | **PUT** /Timesheets/{TimesheetID}/Lines/{TimesheetLineID} | update a timesheet line


## Documentation For Models

 - [Account](Model/Account.md)
 - [Accounts](Model/Accounts.md)
 - [Address](Model/Address.md)
 - [BankAccount](Model/BankAccount.md)
 - [Benefit](Model/Benefit.md)
 - [BenefitLine](Model/BenefitLine.md)
 - [BenefitObject](Model/BenefitObject.md)
 - [Benefits](Model/Benefits.md)
 - [CourtOrderLine](Model/CourtOrderLine.md)
 - [Deduction](Model/Deduction.md)
 - [DeductionLine](Model/DeductionLine.md)
 - [DeductionObject](Model/DeductionObject.md)
 - [Deductions](Model/Deductions.md)
 - [EarningsLine](Model/EarningsLine.md)
 - [EarningsOrder](Model/EarningsOrder.md)
 - [EarningsOrderObject](Model/EarningsOrderObject.md)
 - [EarningsOrders](Model/EarningsOrders.md)
 - [EarningsRate](Model/EarningsRate.md)
 - [EarningsRateObject](Model/EarningsRateObject.md)
 - [EarningsRates](Model/EarningsRates.md)
 - [EarningsTemplate](Model/EarningsTemplate.md)
 - [EarningsTemplateObject](Model/EarningsTemplateObject.md)
 - [Employee](Model/Employee.md)
 - [EmployeeLeave](Model/EmployeeLeave.md)
 - [EmployeeLeaveBalance](Model/EmployeeLeaveBalance.md)
 - [EmployeeLeaveBalances](Model/EmployeeLeaveBalances.md)
 - [EmployeeLeaveObject](Model/EmployeeLeaveObject.md)
 - [EmployeeLeaveType](Model/EmployeeLeaveType.md)
 - [EmployeeLeaveTypeObject](Model/EmployeeLeaveTypeObject.md)
 - [EmployeeLeaveTypes](Model/EmployeeLeaveTypes.md)
 - [EmployeeLeaves](Model/EmployeeLeaves.md)
 - [EmployeeObject](Model/EmployeeObject.md)
 - [EmployeeOpeningBalances](Model/EmployeeOpeningBalances.md)
 - [EmployeeOpeningBalancesObject](Model/EmployeeOpeningBalancesObject.md)
 - [EmployeePayTemplate](Model/EmployeePayTemplate.md)
 - [EmployeePayTemplateObject](Model/EmployeePayTemplateObject.md)
 - [EmployeePayTemplates](Model/EmployeePayTemplates.md)
 - [EmployeeStatutoryLeaveBalance](Model/EmployeeStatutoryLeaveBalance.md)
 - [EmployeeStatutoryLeaveBalanceObject](Model/EmployeeStatutoryLeaveBalanceObject.md)
 - [EmployeeStatutoryLeaveSummary](Model/EmployeeStatutoryLeaveSummary.md)
 - [EmployeeStatutoryLeavesSummaries](Model/EmployeeStatutoryLeavesSummaries.md)
 - [EmployeeStatutorySickLeave](Model/EmployeeStatutorySickLeave.md)
 - [EmployeeStatutorySickLeaveObject](Model/EmployeeStatutorySickLeaveObject.md)
 - [EmployeeStatutorySickLeaves](Model/EmployeeStatutorySickLeaves.md)
 - [EmployeeTax](Model/EmployeeTax.md)
 - [EmployeeTaxObject](Model/EmployeeTaxObject.md)
 - [Employees](Model/Employees.md)
 - [Employment](Model/Employment.md)
 - [EmploymentObject](Model/EmploymentObject.md)
 - [InvalidField](Model/InvalidField.md)
 - [LeaveAccrualLine](Model/LeaveAccrualLine.md)
 - [LeaveEarningsLine](Model/LeaveEarningsLine.md)
 - [LeavePeriod](Model/LeavePeriod.md)
 - [LeavePeriods](Model/LeavePeriods.md)
 - [LeaveType](Model/LeaveType.md)
 - [LeaveTypeObject](Model/LeaveTypeObject.md)
 - [LeaveTypes](Model/LeaveTypes.md)
 - [Pagination](Model/Pagination.md)
 - [PayRun](Model/PayRun.md)
 - [PayRunCalendar](Model/PayRunCalendar.md)
 - [PayRunCalendarObject](Model/PayRunCalendarObject.md)
 - [PayRunCalendars](Model/PayRunCalendars.md)
 - [PayRunObject](Model/PayRunObject.md)
 - [PayRuns](Model/PayRuns.md)
 - [PaymentLine](Model/PaymentLine.md)
 - [PaymentMethod](Model/PaymentMethod.md)
 - [PaymentMethodObject](Model/PaymentMethodObject.md)
 - [Payslip](Model/Payslip.md)
 - [PayslipObject](Model/PayslipObject.md)
 - [Payslips](Model/Payslips.md)
 - [Problem](Model/Problem.md)
 - [Reimbursement](Model/Reimbursement.md)
 - [ReimbursementLine](Model/ReimbursementLine.md)
 - [ReimbursementObject](Model/ReimbursementObject.md)
 - [Reimbursements](Model/Reimbursements.md)
 - [SalaryAndWage](Model/SalaryAndWage.md)
 - [SalaryAndWageObject](Model/SalaryAndWageObject.md)
 - [SalaryAndWages](Model/SalaryAndWages.md)
 - [Settings](Model/Settings.md)
 - [StatutoryDeduction](Model/StatutoryDeduction.md)
 - [StatutoryDeductionCategory](Model/StatutoryDeductionCategory.md)
 - [TaxLine](Model/TaxLine.md)
 - [Timesheet](Model/Timesheet.md)
 - [TimesheetEarningsLine](Model/TimesheetEarningsLine.md)
 - [TimesheetLine](Model/TimesheetLine.md)
 - [TimesheetLineObject](Model/TimesheetLineObject.md)
 - [TimesheetObject](Model/TimesheetObject.md)
 - [Timesheets](Model/Timesheets.md)
 - [TrackingCategories](Model/TrackingCategories.md)
 - [TrackingCategory](Model/TrackingCategory.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **payroll.employees**: Grant read-write access to payroll employees
 - **payroll.employees.read**: Grant read-only access to payroll employees
 - **payroll.payruns**: Grant read-write access to payroll payruns
 - **payroll.payruns.read**: Grant read-only access to payroll payruns
 - **payroll.payslip**: Grant read-write access to payroll payslips
 - **payroll.payslip.read**: Grant read-only access to payroll payslips
 - **payroll.settings**: Grant read-write access to payroll settings
 - **payroll.settings.read**: Grant read-only access to payroll settings
 - **payroll.timesheets**: Grant read-write access to payroll timesheets
 - **payroll.timesheets.read**: Grant read-only access to payroll timesheets


## Author

api@xero.com


