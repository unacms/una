# xero-php-oauth2

## Payroll (Australian) API Documentation

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

  $payrollAuApi = new XeroAPI\XeroPHP\Api\PayrollAuApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  // \XeroAPI\XeroPHP\Models\Accounting\Employee | Request of type Employee
  $employee = new XeroAPI\XeroPHP\Models\PayrollAu\Employee;
  $employee->setFirstName("Fred");
  $employee->setLastName("Potter");
  $employee->setEmail("albus@hogwarts.edu");
  $dateOfBirth = DateTime::createFromFormat('m/d/Y', '05/29/2000');
  $employee->setDateOfBirthAsDate($dateOfBirth);

  $address = new XeroAPI\XeroPHP\Models\PayrollAu\HomeAddress;
  $address->setAddressLine1("101 Green St");
  $address->setCity("Island Bay");
  $address->setRegion(\XeroAPI\XeroPHP\Models\PayrollAu\State::NSW);
  $address->setCountry("AUSTRALIA");
  $address->setPostalCode("6023");
  $employee->setHomeAddress($address);

  $newEmployees = [];		
  array_push($newEmployees, $employee);  

  try {
      $result = $payrollAuApi->createEmployee($xero_tenant_id, $newEmployees);
      print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling payrollAuApi->createEmployee: ', $e->getMessage(), PHP_EOL;
  }

?>
```


## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com/payroll.xro/1.0*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*PayrollAuApi* | [**createEmployee**](Api/PayrollAuApi.md#createemployee) | **POST** /Employees | Use this method to create a payroll employee
*PayrollAuApi* | [**createLeaveApplication**](Api/PayrollAuApi.md#createleaveapplication) | **POST** /LeaveApplications | Use this method to create a Leave Application
*PayrollAuApi* | [**createPayItem**](Api/PayrollAuApi.md#createpayitem) | **POST** /PayItems | Use this method to create a Pay Item
*PayrollAuApi* | [**createPayRun**](Api/PayrollAuApi.md#createpayrun) | **POST** /PayRuns | Use this method to create a PayRun
*PayrollAuApi* | [**createPayrollCalendar**](Api/PayrollAuApi.md#createpayrollcalendar) | **POST** /PayrollCalendars | Use this method to create a Payroll Calendars
*PayrollAuApi* | [**createSuperfund**](Api/PayrollAuApi.md#createsuperfund) | **POST** /Superfunds | Use this method to create a super fund
*PayrollAuApi* | [**createTimesheet**](Api/PayrollAuApi.md#createtimesheet) | **POST** /Timesheets | Use this method to create a timesheet
*PayrollAuApi* | [**getEmployee**](Api/PayrollAuApi.md#getemployee) | **GET** /Employees/{EmployeeId} | searches for an employee by unique id
*PayrollAuApi* | [**getEmployees**](Api/PayrollAuApi.md#getemployees) | **GET** /Employees | searches employees
*PayrollAuApi* | [**getLeaveApplication**](Api/PayrollAuApi.md#getleaveapplication) | **GET** /LeaveApplications/{LeaveApplicationId} | searches for an Leave Application by unique id
*PayrollAuApi* | [**getLeaveApplications**](Api/PayrollAuApi.md#getleaveapplications) | **GET** /LeaveApplications | searches Leave Applications
*PayrollAuApi* | [**getPayItems**](Api/PayrollAuApi.md#getpayitems) | **GET** /PayItems | searches Pay Items
*PayrollAuApi* | [**getPayRun**](Api/PayrollAuApi.md#getpayrun) | **GET** /PayRuns/{PayRunID} | searches for an payrun by unique id
*PayrollAuApi* | [**getPayRuns**](Api/PayrollAuApi.md#getpayruns) | **GET** /PayRuns | searches PayRuns
*PayrollAuApi* | [**getPayrollCalendar**](Api/PayrollAuApi.md#getpayrollcalendar) | **GET** /PayrollCalendars/{PayrollCalendarID} | searches Payroll Calendars
*PayrollAuApi* | [**getPayrollCalendars**](Api/PayrollAuApi.md#getpayrollcalendars) | **GET** /PayrollCalendars | searches Payroll Calendars
*PayrollAuApi* | [**getPayslip**](Api/PayrollAuApi.md#getpayslip) | **GET** /Payslip/{PayslipID} | searches for an payslip by unique id
*PayrollAuApi* | [**getSettings**](Api/PayrollAuApi.md#getsettings) | **GET** /Settings | retrieve settings
*PayrollAuApi* | [**getSuperfund**](Api/PayrollAuApi.md#getsuperfund) | **GET** /Superfunds/{SuperFundID} | searches for an Superfund by unique id
*PayrollAuApi* | [**getSuperfundProducts**](Api/PayrollAuApi.md#getsuperfundproducts) | **GET** /SuperfundProducts | searches SuperfundProducts
*PayrollAuApi* | [**getSuperfunds**](Api/PayrollAuApi.md#getsuperfunds) | **GET** /Superfunds | searches SuperFunds
*PayrollAuApi* | [**getTimesheet**](Api/PayrollAuApi.md#gettimesheet) | **GET** /Timesheets/{TimesheetID} | searches for an timesheet by unique id
*PayrollAuApi* | [**getTimesheets**](Api/PayrollAuApi.md#gettimesheets) | **GET** /Timesheets | searches timesheets
*PayrollAuApi* | [**updateEmployee**](Api/PayrollAuApi.md#updateemployee) | **POST** /Employees/{EmployeeId} | Update an Employee
*PayrollAuApi* | [**updateLeaveApplication**](Api/PayrollAuApi.md#updateleaveapplication) | **POST** /LeaveApplications/{LeaveApplicationId} | Use this method to update a Leave Application
*PayrollAuApi* | [**updatePayRun**](Api/PayrollAuApi.md#updatepayrun) | **POST** /PayRuns/{PayRunID} | Update a PayRun
*PayrollAuApi* | [**updatePayslip**](Api/PayrollAuApi.md#updatepayslip) | **POST** /Payslip/{PayslipID} | Update a Payslip
*PayrollAuApi* | [**updateSuperfund**](Api/PayrollAuApi.md#updatesuperfund) | **POST** /Superfunds/{SuperFundID} | Update a Superfund
*PayrollAuApi* | [**updateTimesheet**](Api/PayrollAuApi.md#updatetimesheet) | **POST** /Timesheets/{TimesheetID} | Update a Timesheet


## Documentation For Models

 - [APIException](Model/APIException.md)
 - [Account](Model/Account.md)
 - [AccountType](Model/AccountType.md)
 - [AllowanceType](Model/AllowanceType.md)
 - [BankAccount](Model/BankAccount.md)
 - [CalendarType](Model/CalendarType.md)
 - [DeductionLine](Model/DeductionLine.md)
 - [DeductionType](Model/DeductionType.md)
 - [DeductionTypeCalculationType](Model/DeductionTypeCalculationType.md)
 - [EarningsLine](Model/EarningsLine.md)
 - [EarningsRate](Model/EarningsRate.md)
 - [EarningsRateCalculationType](Model/EarningsRateCalculationType.md)
 - [EarningsType](Model/EarningsType.md)
 - [Employee](Model/Employee.md)
 - [EmployeeStatus](Model/EmployeeStatus.md)
 - [Employees](Model/Employees.md)
 - [EmploymentBasis](Model/EmploymentBasis.md)
 - [EmploymentTerminationPaymentType](Model/EmploymentTerminationPaymentType.md)
 - [EntitlementFinalPayPayoutType](Model/EntitlementFinalPayPayoutType.md)
 - [HomeAddress](Model/HomeAddress.md)
 - [LeaveAccrualLine](Model/LeaveAccrualLine.md)
 - [LeaveApplication](Model/LeaveApplication.md)
 - [LeaveApplications](Model/LeaveApplications.md)
 - [LeaveBalance](Model/LeaveBalance.md)
 - [LeaveEarningsLine](Model/LeaveEarningsLine.md)
 - [LeaveLine](Model/LeaveLine.md)
 - [LeaveLineCalculationType](Model/LeaveLineCalculationType.md)
 - [LeaveLines](Model/LeaveLines.md)
 - [LeavePeriod](Model/LeavePeriod.md)
 - [LeavePeriodStatus](Model/LeavePeriodStatus.md)
 - [LeaveType](Model/LeaveType.md)
 - [LeaveTypeContributionType](Model/LeaveTypeContributionType.md)
 - [ManualTaxType](Model/ManualTaxType.md)
 - [OpeningBalances](Model/OpeningBalances.md)
 - [PayItem](Model/PayItem.md)
 - [PayItems](Model/PayItems.md)
 - [PayRun](Model/PayRun.md)
 - [PayRunStatus](Model/PayRunStatus.md)
 - [PayRuns](Model/PayRuns.md)
 - [PayTemplate](Model/PayTemplate.md)
 - [PaymentFrequencyType](Model/PaymentFrequencyType.md)
 - [PayrollCalendar](Model/PayrollCalendar.md)
 - [PayrollCalendars](Model/PayrollCalendars.md)
 - [Payslip](Model/Payslip.md)
 - [PayslipLines](Model/PayslipLines.md)
 - [PayslipObject](Model/PayslipObject.md)
 - [PayslipSummary](Model/PayslipSummary.md)
 - [Payslips](Model/Payslips.md)
 - [RateType](Model/RateType.md)
 - [ReimbursementLine](Model/ReimbursementLine.md)
 - [ReimbursementLines](Model/ReimbursementLines.md)
 - [ReimbursementType](Model/ReimbursementType.md)
 - [ResidencyStatus](Model/ResidencyStatus.md)
 - [Settings](Model/Settings.md)
 - [SettingsObject](Model/SettingsObject.md)
 - [SettingsTrackingCategories](Model/SettingsTrackingCategories.md)
 - [SettingsTrackingCategoriesEmployeeGroups](Model/SettingsTrackingCategoriesEmployeeGroups.md)
 - [SettingsTrackingCategoriesTimesheetCategories](Model/SettingsTrackingCategoriesTimesheetCategories.md)
 - [State](Model/State.md)
 - [SuperFund](Model/SuperFund.md)
 - [SuperFundProduct](Model/SuperFundProduct.md)
 - [SuperFundProducts](Model/SuperFundProducts.md)
 - [SuperFundType](Model/SuperFundType.md)
 - [SuperFunds](Model/SuperFunds.md)
 - [SuperLine](Model/SuperLine.md)
 - [SuperMembership](Model/SuperMembership.md)
 - [SuperannuationCalculationType](Model/SuperannuationCalculationType.md)
 - [SuperannuationContributionType](Model/SuperannuationContributionType.md)
 - [SuperannuationLine](Model/SuperannuationLine.md)
 - [TFNExemptionType](Model/TFNExemptionType.md)
 - [TaxDeclaration](Model/TaxDeclaration.md)
 - [TaxLine](Model/TaxLine.md)
 - [Timesheet](Model/Timesheet.md)
 - [TimesheetLine](Model/TimesheetLine.md)
 - [TimesheetObject](Model/TimesheetObject.md)
 - [TimesheetStatus](Model/TimesheetStatus.md)
 - [Timesheets](Model/Timesheets.md)
 - [ValidationError](Model/ValidationError.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **email**: Grant read-only access to your email
 - **openid**: Grant read-only access to your open id
 - **profile**: your profile information
 - **accounting.transactions**: Grant read-write access to bank transactions, credit notes, invoices, repeating invoices
 - **accounting.transactions.read**: Grant read-only access to invoices
 - **accounting.reports.read**: Grant read-only access to accounting reports
 - **accounting.journals.read**: Grant read-only access to journals
 - **accounting.settings**: Grant read-write access to organisation and account settings
 - **accounting.settings.read**: Grant read-only access to organisation and account settings
 - **accounting.contacts**: Grant read-write access to contacts and contact groups
 - **accounting.contacts.read**: Grant read-only access to contacts and contact groups
 - **accounting.attachments**: Grant read-write access to attachments
 - **accounting.attachments.read**: Grant read-only access to attachments
 - **assets assets.read**: Grant read-only access to fixed assets
 - **bankfeeds**: Grant read-write access to bankfeeds
 - **files**: Grant read-write access to files and folders
 - **files.read**: Grant read-only access to files and folders
 - **payroll**: Grant read-write access to payroll
 - **payroll.read**: Grant read-only access to payroll
 - **payroll.employees**: Grant read-write access to payroll employees
 - **payroll.employees.read**: Grant read-only access to payroll employees
 - **payroll.leaveapplications**: Grant read-write access to payroll leaveapplications
 - **payroll.leaveapplications.read**: Grant read-only access to payroll leaveapplications
 - **payroll.payitems**: Grant read-write access to payroll payitems
 - **payroll.payitems.read**: Grant read-only access to payroll payitems
 - **payroll.payrollcalendars**: Grant read-write access to payroll calendars
 - **payroll.payrollcalendars.read**: Grant read-only access to payroll calendars
 - **payroll.payruns**: Grant read-write access to payroll payruns
 - **payroll.payruns.read**: Grant read-only access to payroll payruns
 - **payroll.payslip**: Grant read-write access to payroll payslips
 - **payroll.payslip.read**: Grant read-only access to payroll payslips
 - **payroll.settings.read**: Grant read-only access to payroll settings
 - **payroll.superfunds**: Grant read-write access to payroll superfunds
 - **payroll.superfunds.read**: Grant read-only access to payroll superfunds
 - **payroll.superfundproducts.read**: Grant read-only access to payroll superfundproducts
 - **payroll.timesheets**: Grant read-write access to payroll timesheets
 - **payroll.timesheets.read**: Grant read-only access to payroll timesheets
 - **paymentservices**: Grant read-write access to payment services
 - **projects**: Grant read-write access to projects
 - **projects.read**: Grant read-only access to projects


## Author

api@xero.com
