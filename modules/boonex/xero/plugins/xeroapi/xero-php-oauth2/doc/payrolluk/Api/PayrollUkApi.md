# XeroAPI\XeroPHP\PayrollUkApi

All URIs are relative to *https://api.xero.com/payroll.xro/2.0*

Method | HTTP request | Description
------------- | ------------- | -------------
[**approveTimesheet**](PayrollUkApi.md#approveTimesheet) | **POST** /Timesheets/{TimesheetID}/Approve | Approves a specific timesheet
[**createBenefit**](PayrollUkApi.md#createBenefit) | **POST** /Benefits | Creates a new employee benefit
[**createDeduction**](PayrollUkApi.md#createDeduction) | **POST** /Deductions | Creates a new deduction
[**createEarningsRate**](PayrollUkApi.md#createEarningsRate) | **POST** /EarningsRates | Creates a new earnings rate
[**createEmployee**](PayrollUkApi.md#createEmployee) | **POST** /Employees | Creates employees
[**createEmployeeEarningsTemplate**](PayrollUkApi.md#createEmployeeEarningsTemplate) | **POST** /Employees/{EmployeeID}/PayTemplates/earnings | Creates an earnings template records for a specific employee
[**createEmployeeLeave**](PayrollUkApi.md#createEmployeeLeave) | **POST** /Employees/{EmployeeID}/Leave | Creates leave records for a specific employee
[**createEmployeeLeaveType**](PayrollUkApi.md#createEmployeeLeaveType) | **POST** /Employees/{EmployeeID}/LeaveTypes | Creates employee leave type records
[**createEmployeeOpeningBalances**](PayrollUkApi.md#createEmployeeOpeningBalances) | **POST** /Employees/{EmployeeID}/ukopeningbalances | Creates an opening balance for a specific employee
[**createEmployeePaymentMethod**](PayrollUkApi.md#createEmployeePaymentMethod) | **POST** /Employees/{EmployeeID}/PaymentMethods | Creates an employee payment method
[**createEmployeeSalaryAndWage**](PayrollUkApi.md#createEmployeeSalaryAndWage) | **POST** /Employees/{EmployeeID}/SalaryAndWages | Creates a salary and wage record for a specific employee
[**createEmployeeStatutorySickLeave**](PayrollUkApi.md#createEmployeeStatutorySickLeave) | **POST** /StatutoryLeaves/Sick | Creates statutory sick leave records
[**createEmployment**](PayrollUkApi.md#createEmployment) | **POST** /Employees/{EmployeeID}/Employment | Creates employment detail for a specific employee using a unique employee ID
[**createLeaveType**](PayrollUkApi.md#createLeaveType) | **POST** /LeaveTypes | Creates a new leave type
[**createMultipleEmployeeEarningsTemplate**](PayrollUkApi.md#createMultipleEmployeeEarningsTemplate) | **POST** /Employees/{EmployeeID}/paytemplateearnings | Creates multiple earnings template records for a specific employee using a unique employee ID
[**createPayRunCalendar**](PayrollUkApi.md#createPayRunCalendar) | **POST** /PayRunCalendars | Creates a new payrun calendar
[**createReimbursement**](PayrollUkApi.md#createReimbursement) | **POST** /Reimbursements | Creates a new reimbursement
[**createTimesheet**](PayrollUkApi.md#createTimesheet) | **POST** /Timesheets | Creates a new timesheet
[**createTimesheetLine**](PayrollUkApi.md#createTimesheetLine) | **POST** /Timesheets/{TimesheetID}/Lines | Creates a new timesheet line for a specific timesheet using a unique timesheet ID
[**deleteEmployeeEarningsTemplate**](PayrollUkApi.md#deleteEmployeeEarningsTemplate) | **DELETE** /Employees/{EmployeeID}/PayTemplates/earnings/{PayTemplateEarningID} | Deletes a specific employee&#39;s earnings template record
[**deleteEmployeeLeave**](PayrollUkApi.md#deleteEmployeeLeave) | **DELETE** /Employees/{EmployeeID}/Leave/{LeaveID} | Deletes a specific employee&#39;s leave record
[**deleteEmployeeSalaryAndWage**](PayrollUkApi.md#deleteEmployeeSalaryAndWage) | **DELETE** /Employees/{EmployeeID}/SalaryAndWages/{SalaryAndWagesID} | Deletes a salary and wages record for a specific employee
[**deleteTimesheet**](PayrollUkApi.md#deleteTimesheet) | **DELETE** /Timesheets/{TimesheetID} | Deletes a specific timesheet
[**deleteTimesheetLine**](PayrollUkApi.md#deleteTimesheetLine) | **DELETE** /Timesheets/{TimesheetID}/Lines/{TimesheetLineID} | Deletes a specific timesheet line
[**getBenefit**](PayrollUkApi.md#getBenefit) | **GET** /Benefits/{id} | Retrieves a specific benefit by using a unique benefit ID
[**getBenefits**](PayrollUkApi.md#getBenefits) | **GET** /Benefits | Retrieves employee benefits
[**getDeduction**](PayrollUkApi.md#getDeduction) | **GET** /Deductions/{deductionId} | Retrieves a specific deduction by using a unique deduction ID
[**getDeductions**](PayrollUkApi.md#getDeductions) | **GET** /Deductions | Retrieves deductions
[**getEarningsOrder**](PayrollUkApi.md#getEarningsOrder) | **GET** /EarningsOrders/{id} | Retrieves a specific earnings orders by using a unique earnings orders id
[**getEarningsOrders**](PayrollUkApi.md#getEarningsOrders) | **GET** /EarningsOrders | Retrieves earnings orders
[**getEarningsRate**](PayrollUkApi.md#getEarningsRate) | **GET** /EarningsRates/{EarningsRateID} | Retrieves a specific earnings rates by using a unique earnings rate id
[**getEarningsRates**](PayrollUkApi.md#getEarningsRates) | **GET** /EarningsRates | Retrieves earnings rates
[**getEmployee**](PayrollUkApi.md#getEmployee) | **GET** /Employees/{EmployeeID} | Retrieves specific employees by using a unique employee ID
[**getEmployeeLeave**](PayrollUkApi.md#getEmployeeLeave) | **GET** /Employees/{EmployeeID}/Leave/{LeaveID} | Retrieves a specific employee&#39;s leave record using a unique employee ID
[**getEmployeeLeaveBalances**](PayrollUkApi.md#getEmployeeLeaveBalances) | **GET** /Employees/{EmployeeID}/LeaveBalances | Retrieves a specific employee&#39;s leave balances using a unique employee ID
[**getEmployeeLeavePeriods**](PayrollUkApi.md#getEmployeeLeavePeriods) | **GET** /Employees/{EmployeeID}/LeavePeriods | Retrieves a specific employee&#39;s leave periods using a unique employee ID
[**getEmployeeLeaveTypes**](PayrollUkApi.md#getEmployeeLeaveTypes) | **GET** /Employees/{EmployeeID}/LeaveTypes | Retrieves a specific employee&#39;s leave types using a unique employee ID
[**getEmployeeLeaves**](PayrollUkApi.md#getEmployeeLeaves) | **GET** /Employees/{EmployeeID}/Leave | Retrieves a specific employee&#39;s leave records using a unique employee ID
[**getEmployeeOpeningBalances**](PayrollUkApi.md#getEmployeeOpeningBalances) | **GET** /Employees/{EmployeeID}/ukopeningbalances | Retrieves a specific employee&#39;s openingbalances using a unique employee ID
[**getEmployeePayTemplate**](PayrollUkApi.md#getEmployeePayTemplate) | **GET** /Employees/{EmployeeID}/PayTemplates | Retrieves a specific employee pay templates using a unique employee ID
[**getEmployeePaymentMethod**](PayrollUkApi.md#getEmployeePaymentMethod) | **GET** /Employees/{EmployeeID}/PaymentMethods | Retrieves a specific employee&#39;s payment method using a unique employee ID
[**getEmployeeSalaryAndWage**](PayrollUkApi.md#getEmployeeSalaryAndWage) | **GET** /Employees/{EmployeeID}/SalaryAndWages/{SalaryAndWagesID} | Retrieves a specific salary and wages record for a specific employee using a unique salary and wage id
[**getEmployeeSalaryAndWages**](PayrollUkApi.md#getEmployeeSalaryAndWages) | **GET** /Employees/{EmployeeID}/SalaryAndWages | Retrieves a specific employee&#39;s salary and wages by using a unique employee ID
[**getEmployeeStatutoryLeaveBalances**](PayrollUkApi.md#getEmployeeStatutoryLeaveBalances) | **GET** /Employees/{EmployeeID}/StatutoryLeaveBalance | Retrieves a specific employee&#39;s leave balances using a unique employee ID
[**getEmployeeStatutorySickLeave**](PayrollUkApi.md#getEmployeeStatutorySickLeave) | **GET** /StatutoryLeaves/Sick/{StatutorySickLeaveID} | Retrieves a statutory sick leave for an employee
[**getEmployeeTax**](PayrollUkApi.md#getEmployeeTax) | **GET** /Employees/{EmployeeID}/Tax | Retrieves tax records for a specific employee using a unique employee ID
[**getEmployees**](PayrollUkApi.md#getEmployees) | **GET** /Employees | Retrieves employees
[**getLeaveType**](PayrollUkApi.md#getLeaveType) | **GET** /LeaveTypes/{LeaveTypeID} | Retrieves a specific leave type by using a unique leave type ID
[**getLeaveTypes**](PayrollUkApi.md#getLeaveTypes) | **GET** /LeaveTypes | Retrieves leave types
[**getPayRun**](PayrollUkApi.md#getPayRun) | **GET** /PayRuns/{PayRunID} | Retrieves a specific pay run by using a unique pay run ID
[**getPayRunCalendar**](PayrollUkApi.md#getPayRunCalendar) | **GET** /PayRunCalendars/{PayRunCalendarID} | Retrieves a specific payrun calendar by using a unique payrun calendar ID
[**getPayRunCalendars**](PayrollUkApi.md#getPayRunCalendars) | **GET** /PayRunCalendars | Retrieves payrun calendars
[**getPayRuns**](PayrollUkApi.md#getPayRuns) | **GET** /PayRuns | Retrieves pay runs
[**getPaySlip**](PayrollUkApi.md#getPaySlip) | **GET** /Payslips/{PayslipID} | Retrieves a specific payslip by using a unique payslip ID
[**getPaySlips**](PayrollUkApi.md#getPaySlips) | **GET** /Payslips | Retrieves payslips
[**getReimbursement**](PayrollUkApi.md#getReimbursement) | **GET** /Reimbursements/{ReimbursementID} | Retrieves a specific reimbursement by using a unique reimbursement id
[**getReimbursements**](PayrollUkApi.md#getReimbursements) | **GET** /Reimbursements | Retrieves reimbursements
[**getSettings**](PayrollUkApi.md#getSettings) | **GET** /Settings | Retrieves payroll settings
[**getStatutoryLeaveSummary**](PayrollUkApi.md#getStatutoryLeaveSummary) | **GET** /StatutoryLeaves/Summary/{EmployeeID} | Retrieves a specific employee&#39;s summary of statutory leaves using a unique employee ID
[**getTimesheet**](PayrollUkApi.md#getTimesheet) | **GET** /Timesheets/{TimesheetID} | Retrieve a specific timesheet by using a unique timesheet ID
[**getTimesheets**](PayrollUkApi.md#getTimesheets) | **GET** /Timesheets | Retrieves timesheets
[**getTrackingCategories**](PayrollUkApi.md#getTrackingCategories) | **GET** /Settings/trackingCategories | Retrieves tracking categories
[**revertTimesheet**](PayrollUkApi.md#revertTimesheet) | **POST** /Timesheets/{TimesheetID}/RevertToDraft | Reverts a specific timesheet to draft
[**updateEmployee**](PayrollUkApi.md#updateEmployee) | **PUT** /Employees/{EmployeeID} | Updates a specific employee&#39;s detail
[**updateEmployeeEarningsTemplate**](PayrollUkApi.md#updateEmployeeEarningsTemplate) | **PUT** /Employees/{EmployeeID}/PayTemplates/earnings/{PayTemplateEarningID} | Updates a specific employee&#39;s earnings template records
[**updateEmployeeLeave**](PayrollUkApi.md#updateEmployeeLeave) | **PUT** /Employees/{EmployeeID}/Leave/{LeaveID} | Updates a specific employee&#39;s leave records
[**updateEmployeeOpeningBalances**](PayrollUkApi.md#updateEmployeeOpeningBalances) | **PUT** /Employees/{EmployeeID}/ukopeningbalances | Updates a specific employee&#39;s opening balances
[**updateEmployeeSalaryAndWage**](PayrollUkApi.md#updateEmployeeSalaryAndWage) | **PUT** /Employees/{EmployeeID}/SalaryAndWages/{SalaryAndWagesID} | Updates salary and wages record for a specific employee
[**updatePayRun**](PayrollUkApi.md#updatePayRun) | **PUT** /PayRuns/{PayRunID} | Updates a specific pay run
[**updateTimesheetLine**](PayrollUkApi.md#updateTimesheetLine) | **PUT** /Timesheets/{TimesheetID}/Lines/{TimesheetLineID} | Updates a specific timesheet line for a specific timesheet


# **approveTimesheet**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject approveTimesheet($xero_tenant_id, $timesheet_id)

Approves a specific timesheet

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet

try {
    $result = $apiInstance->approveTimesheet($xero_tenant_id, $timesheet_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->approveTimesheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject**](../Model/TimesheetObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBenefit**
> \XeroAPI\XeroPHP\Models\PayrollUk\BenefitObject createBenefit($xero_tenant_id, $benefit)

Creates a new employee benefit

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$benefit = { "name": "My Big Bennie", "category": "StakeholderPension", "liabilityAccountId": "e0faa299-ca0d-4b0a-9e32-0dfabdf9179a", "expenseAccountId": "4b03500d-32fd-4616-8d70-e1e56e0519c6", "standardAmount": 50, "percentage": 25, "calculationType": "PercentageOfGross" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Benefit | 

try {
    $result = $apiInstance->createBenefit($xero_tenant_id, $benefit);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createBenefit: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **benefit** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Benefit**](../Model/Benefit.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\BenefitObject**](../Model/BenefitObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createDeduction**
> \XeroAPI\XeroPHP\Models\PayrollUk\DeductionObject createDeduction($xero_tenant_id, $deduction)

Creates a new deduction

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$deduction = { "deductionName": "My new deduction", "deductionCategory": "SalarySacrifice", "liabilityAccountId": "e0faa299-ca0d-4b0a-9e32-0dfabdf9179a", "calculationType": "FixedAmount" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Deduction | 

try {
    $result = $apiInstance->createDeduction($xero_tenant_id, $deduction);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createDeduction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **deduction** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Deduction**](../Model/Deduction.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\DeductionObject**](../Model/DeductionObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEarningsRate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsRateObject createEarningsRate($xero_tenant_id, $earnings_rate)

Creates a new earnings rate

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$earnings_rate = { "name": "My Earnings Rate", "earningsType": "RegularEarnings", "rateType": "RatePerUnit", "typeOfUnits": "hours", "expenseAccountID": "4b03500d-32fd-4616-8d70-e1e56e0519c6" }; // \XeroAPI\XeroPHP\Models\PayrollUk\EarningsRate | 

try {
    $result = $apiInstance->createEarningsRate($xero_tenant_id, $earnings_rate);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEarningsRate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **earnings_rate** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsRate**](../Model/EarningsRate.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsRateObject**](../Model/EarningsRateObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployee**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject createEmployee($xero_tenant_id, $employee)

Creates employees

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee = { "title":"Mr", "firstName":"Mike", "lastName":"Fancy", "dateOfBirth":"1999-01-01", "address":{ "addressLine1":"101 Green St", "city":"San Francisco", "postCode":"6TGR4F", "country":"UK" }, "email":"mike@starkindustries.com", "gender":"M" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Employee | 

try {
    $result = $apiInstance->createEmployee($xero_tenant_id, $employee);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployee: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Employee**](../Model/Employee.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject**](../Model/EmployeeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeEarningsTemplate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplateObject createEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $earnings_template)

Creates an earnings template records for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$earnings_template = new \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate(); // \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate | 

try {
    $result = $apiInstance->createEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $earnings_template);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeEarningsTemplate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **earnings_template** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate**](../Model/EarningsTemplate.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplateObject**](../Model/EarningsTemplateObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject createEmployeeLeave($xero_tenant_id, $employee_id, $employee_leave)

Creates leave records for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employee_leave = { "leaveTypeID": "1d2778ee-86ea-45c0-bbf8-1045485f6b3f", "description": "Creating a Description", "startDate": "2020-03-24", "endDate": "2020-03-26" }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeave | 

try {
    $result = $apiInstance->createEmployeeLeave($xero_tenant_id, $employee_id, $employee_leave);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employee_leave** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeave**](../Model/EmployeeLeave.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject**](../Model/EmployeeLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeLeaveType**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveTypeObject createEmployeeLeaveType($xero_tenant_id, $employee_id, $employee_leave_type)

Creates employee leave type records

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employee_leave_type = { "leaveTypeID": "4918f233-bd31-43f9-9633-bcc6de1178f2", "scheduleOfAccrual": "BeginningOfCalendarYear", "hoursAccruedAnnually": 10 }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveType | 

try {
    $result = $apiInstance->createEmployeeLeaveType($xero_tenant_id, $employee_id, $employee_leave_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeLeaveType: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employee_leave_type** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveType**](../Model/EmployeeLeaveType.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveTypeObject**](../Model/EmployeeLeaveTypeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeOpeningBalances**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject createEmployeeOpeningBalances($xero_tenant_id, $employee_id, $employee_opening_balances)

Creates an opening balance for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employee_opening_balances = { "statutoryAdoptionPay": 10, "statutoryMaternityPay": 10, "statutoryPaternityPay": 10, "statutorySharedParentalPay": 10, "statutorySickPay": 10, "priorEmployeeNumber": 10 }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalances | 

try {
    $result = $apiInstance->createEmployeeOpeningBalances($xero_tenant_id, $employee_id, $employee_opening_balances);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeOpeningBalances: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employee_opening_balances** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalances**](../Model/EmployeeOpeningBalances.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject**](../Model/EmployeeOpeningBalancesObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeePaymentMethod**
> \XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethodObject createEmployeePaymentMethod($xero_tenant_id, $employee_id, $payment_method)

Creates an employee payment method

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$payment_method = { "paymentMethod": "Electronically", "bankAccounts": [ { "accountName": "Sid BofA", "accountNumber": "24987654", "sortCode": "287654" } ] }; // \XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethod | 

try {
    $result = $apiInstance->createEmployeePaymentMethod($xero_tenant_id, $employee_id, $payment_method);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeePaymentMethod: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **payment_method** | [**\XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethod**](../Model/PaymentMethod.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethodObject**](../Model/PaymentMethodObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeSalaryAndWage**
> \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWageObject createEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wage)

Creates a salary and wage record for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$salary_and_wage = { "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnitsPerWeek": 2, "ratePerUnit": 10, "numberOfUnitsPerDay": 2, "effectiveFrom": "2020-05-01", "annualSalary": 100, "status": "ACTIVE", "paymentType": "Salary" }; // \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWage | 

try {
    $result = $apiInstance->createEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wage);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeSalaryAndWage: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **salary_and_wage** | [**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWage**](../Model/SalaryAndWage.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWageObject**](../Model/SalaryAndWageObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployeeStatutorySickLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeaveObject createEmployeeStatutorySickLeave($xero_tenant_id, $employee_statutory_sick_leave)

Creates statutory sick leave records

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_statutory_sick_leave = { "employeeID": "aad6b292-7b94-408b-93f6-e489867e3fb0", "leaveTypeID": "aab78802-e9d3-4bbd-bc87-df858054988f", "startDate": "2020-04-21", "endDate": "2020-04-24", "workPattern": [ "Monday", "Tuesday", "Wednesday", "Thursday", "Friday" ], "isPregnancyRelated": false, "sufficientNotice": true }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeave | 

try {
    $result = $apiInstance->createEmployeeStatutorySickLeave($xero_tenant_id, $employee_statutory_sick_leave);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployeeStatutorySickLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_statutory_sick_leave** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeave**](../Model/EmployeeStatutorySickLeave.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeaveObject**](../Model/EmployeeStatutorySickLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployment**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmploymentObject createEmployment($xero_tenant_id, $employee_id, $employment)

Creates employment detail for a specific employee using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employment = { "PayrollCalendarID": "216d80e6-af55-47b1-b718-9457c3f5d2fe", "StartDate": "2020-04-01", "EmployeeNumber": "123ABC", "NICategory": "A" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Employment | 

try {
    $result = $apiInstance->createEmployment($xero_tenant_id, $employee_id, $employment);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createEmployment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employment** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Employment**](../Model/Employment.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmploymentObject**](../Model/EmploymentObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createLeaveType**
> \XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypeObject createLeaveType($xero_tenant_id, $leave_type)

Creates a new leave type

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$leave_type = { "name": "My opebvwbfxf Leave", "isPaidLeave": false, "showOnPayslip": true }; // \XeroAPI\XeroPHP\Models\PayrollUk\LeaveType | 

try {
    $result = $apiInstance->createLeaveType($xero_tenant_id, $leave_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createLeaveType: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **leave_type** | [**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveType**](../Model/LeaveType.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypeObject**](../Model/LeaveTypeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createMultipleEmployeeEarningsTemplate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeePayTemplates createMultipleEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $earnings_template)

Creates multiple earnings template records for a specific employee using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$earnings_template = [ { "ratePerUnit":20.0, "numberOfUnits":8.0, "earningsRateID":"87f5b43a-cf51-4b74-92de-94c819e82d27" }, { "ratePerUnit":20.0, "numberOfUnits":8.0, "earningsRateID":"973365f3-66b2-4c33-8ae6-14b75f78f68b" } ]; // \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate[] | 

try {
    $result = $apiInstance->createMultipleEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $earnings_template);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createMultipleEmployeeEarningsTemplate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **earnings_template** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate[]**](../Model/EarningsTemplate.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeePayTemplates**](../Model/EmployeePayTemplates.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPayRunCalendar**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendarObject createPayRunCalendar($xero_tenant_id, $pay_run_calendar)

Creates a new payrun calendar

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$pay_run_calendar = { "name": "My Weekly Cal", "calendarType": "Weekly", "periodStartDate": "2020-05-01", "paymentDate": "2020-05-15" }; // \XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendar | 

try {
    $result = $apiInstance->createPayRunCalendar($xero_tenant_id, $pay_run_calendar);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createPayRunCalendar: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **pay_run_calendar** | [**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendar**](../Model/PayRunCalendar.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendarObject**](../Model/PayRunCalendarObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createReimbursement**
> \XeroAPI\XeroPHP\Models\PayrollUk\ReimbursementObject createReimbursement($xero_tenant_id, $reimbursement)

Creates a new reimbursement

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$reimbursement = { "name": "My new Reimburse", "accountID": "9ee28149-32a9-4661-8eab-a28738696983" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Reimbursement | 

try {
    $result = $apiInstance->createReimbursement($xero_tenant_id, $reimbursement);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createReimbursement: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **reimbursement** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Reimbursement**](../Model/Reimbursement.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\ReimbursementObject**](../Model/ReimbursementObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTimesheet**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject createTimesheet($xero_tenant_id, $timesheet)

Creates a new timesheet

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet = { "payrollCalendarID": "216d80e6-af55-47b1-b718-9457c3f5d2fe", "employeeID": "aad6b292-7b94-408b-93f6-e489867e3fb0", "startDate": "2020-04-13", "endDate": "2020-04-19", "timesheetLines": [ { "date": "2020-04-13", "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnits": 8 }, { "date": "2020-04-15", "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnits": 6 } ] }; // \XeroAPI\XeroPHP\Models\PayrollUk\Timesheet | 

try {
    $result = $apiInstance->createTimesheet($xero_tenant_id, $timesheet);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createTimesheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Timesheet**](../Model/Timesheet.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject**](../Model/TimesheetObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTimesheetLine**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLineObject createTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line)

Creates a new timesheet line for a specific timesheet using a unique timesheet ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet
$timesheet_line = { "date": "2020-04-14", "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnits": 1 }; // \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine | 

try {
    $result = $apiInstance->createTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->createTimesheetLine: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |
 **timesheet_line** | [**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine**](../Model/TimesheetLine.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLineObject**](../Model/TimesheetLineObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteEmployeeEarningsTemplate**
> deleteEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $pay_template_earning_id)

Deletes a specific employee's earnings template record

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$pay_template_earning_id = 3fa85f64-5717-4562-b3fc-2c963f66afa6; // string | Id for single pay template earnings object

try {
    $apiInstance->deleteEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $pay_template_earning_id);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->deleteEmployeeEarningsTemplate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **pay_template_earning_id** | [**string**](../Model/.md)| Id for single pay template earnings object |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteEmployeeLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject deleteEmployeeLeave($xero_tenant_id, $employee_id, $leave_id)

Deletes a specific employee's leave record

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$leave_id = c4be24e5-e840-4c92-9eaa-2d86cd596314; // string | Leave id for single object

try {
    $result = $apiInstance->deleteEmployeeLeave($xero_tenant_id, $employee_id, $leave_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->deleteEmployeeLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **leave_id** | [**string**](../Model/.md)| Leave id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject**](../Model/EmployeeLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteEmployeeSalaryAndWage**
> deleteEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id)

Deletes a salary and wages record for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$salary_and_wages_id = 3fa85f64-5717-4562-b3fc-2c963f66afa6; // string | Id for single salary and wages object

try {
    $apiInstance->deleteEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->deleteEmployeeSalaryAndWage: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **salary_and_wages_id** | [**string**](../Model/.md)| Id for single salary and wages object |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTimesheet**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine deleteTimesheet($xero_tenant_id, $timesheet_id)

Deletes a specific timesheet

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet

try {
    $result = $apiInstance->deleteTimesheet($xero_tenant_id, $timesheet_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->deleteTimesheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine**](../Model/TimesheetLine.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTimesheetLine**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine deleteTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line_id)

Deletes a specific timesheet line

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet
$timesheet_line_id = 'timesheet_line_id_example'; // string | Identifier for the timesheet line

try {
    $result = $apiInstance->deleteTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->deleteTimesheetLine: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |
 **timesheet_line_id** | [**string**](../Model/.md)| Identifier for the timesheet line |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine**](../Model/TimesheetLine.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBenefit**
> \XeroAPI\XeroPHP\Models\PayrollUk\BenefitObject getBenefit($xero_tenant_id, $id)

Retrieves a specific benefit by using a unique benefit ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$id = 'id_example'; // string | Identifier for the benefit

try {
    $result = $apiInstance->getBenefit($xero_tenant_id, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getBenefit: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **id** | [**string**](../Model/.md)| Identifier for the benefit |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\BenefitObject**](../Model/BenefitObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBenefits**
> \XeroAPI\XeroPHP\Models\PayrollUk\Benefits getBenefits($xero_tenant_id, $page)

Retrieves employee benefits

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getBenefits($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getBenefits: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Benefits**](../Model/Benefits.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getDeduction**
> \XeroAPI\XeroPHP\Models\PayrollUk\DeductionObject getDeduction($xero_tenant_id, $deduction_id)

Retrieves a specific deduction by using a unique deduction ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$deduction_id = 'deduction_id_example'; // string | Identifier for the deduction

try {
    $result = $apiInstance->getDeduction($xero_tenant_id, $deduction_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getDeduction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **deduction_id** | [**string**](../Model/.md)| Identifier for the deduction |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\DeductionObject**](../Model/DeductionObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getDeductions**
> \XeroAPI\XeroPHP\Models\PayrollUk\Deductions getDeductions($xero_tenant_id, $page)

Retrieves deductions

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getDeductions($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getDeductions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Deductions**](../Model/Deductions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEarningsOrder**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsOrderObject getEarningsOrder($xero_tenant_id, $id)

Retrieves a specific earnings orders by using a unique earnings orders id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$id = 'id_example'; // string | Identifier for the deduction

try {
    $result = $apiInstance->getEarningsOrder($xero_tenant_id, $id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEarningsOrder: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **id** | [**string**](../Model/.md)| Identifier for the deduction |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsOrderObject**](../Model/EarningsOrderObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEarningsOrders**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsOrders getEarningsOrders($xero_tenant_id, $page)

Retrieves earnings orders

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getEarningsOrders($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEarningsOrders: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsOrders**](../Model/EarningsOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEarningsRate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsRateObject getEarningsRate($xero_tenant_id, $earnings_rate_id)

Retrieves a specific earnings rates by using a unique earnings rate id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$earnings_rate_id = 'earnings_rate_id_example'; // string | Identifier for the earnings rate

try {
    $result = $apiInstance->getEarningsRate($xero_tenant_id, $earnings_rate_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEarningsRate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **earnings_rate_id** | [**string**](../Model/.md)| Identifier for the earnings rate |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsRateObject**](../Model/EarningsRateObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEarningsRates**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsRates getEarningsRates($xero_tenant_id, $page)

Retrieves earnings rates

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getEarningsRates($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEarningsRates: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsRates**](../Model/EarningsRates.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployee**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject getEmployee($xero_tenant_id, $employee_id)

Retrieves specific employees by using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployee($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployee: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject**](../Model/EmployeeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject getEmployeeLeave($xero_tenant_id, $employee_id, $leave_id)

Retrieves a specific employee's leave record using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$leave_id = c4be24e5-e840-4c92-9eaa-2d86cd596314; // string | Leave id for single object

try {
    $result = $apiInstance->getEmployeeLeave($xero_tenant_id, $employee_id, $leave_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **leave_id** | [**string**](../Model/.md)| Leave id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject**](../Model/EmployeeLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeLeaveBalances**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveBalances getEmployeeLeaveBalances($xero_tenant_id, $employee_id)

Retrieves a specific employee's leave balances using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeeLeaveBalances($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeLeaveBalances: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveBalances**](../Model/EmployeeLeaveBalances.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeLeavePeriods**
> \XeroAPI\XeroPHP\Models\PayrollUk\LeavePeriods getEmployeeLeavePeriods($xero_tenant_id, $employee_id, $start_date, $end_date)

Retrieves a specific employee's leave periods using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$start_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Filter by start date
$end_date = Johnson; // \DateTime | Filter by end date

try {
    $result = $apiInstance->getEmployeeLeavePeriods($xero_tenant_id, $employee_id, $start_date, $end_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeLeavePeriods: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **start_date** | **\DateTime**| Filter by start date | [optional]
 **end_date** | **\DateTime**| Filter by end date | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\LeavePeriods**](../Model/LeavePeriods.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeLeaveTypes**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveTypes getEmployeeLeaveTypes($xero_tenant_id, $employee_id)

Retrieves a specific employee's leave types using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeeLeaveTypes($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeLeaveTypes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveTypes**](../Model/EmployeeLeaveTypes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeLeaves**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaves getEmployeeLeaves($xero_tenant_id, $employee_id)

Retrieves a specific employee's leave records using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeeLeaves($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeLeaves: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaves**](../Model/EmployeeLeaves.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeOpeningBalances**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject getEmployeeOpeningBalances($xero_tenant_id, $employee_id)

Retrieves a specific employee's openingbalances using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeeOpeningBalances($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeOpeningBalances: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject**](../Model/EmployeeOpeningBalancesObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeePayTemplate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeePayTemplateObject getEmployeePayTemplate($xero_tenant_id, $employee_id)

Retrieves a specific employee pay templates using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeePayTemplate($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeePayTemplate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeePayTemplateObject**](../Model/EmployeePayTemplateObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeePaymentMethod**
> \XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethodObject getEmployeePaymentMethod($xero_tenant_id, $employee_id)

Retrieves a specific employee's payment method using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeePaymentMethod($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeePaymentMethod: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PaymentMethodObject**](../Model/PaymentMethodObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeSalaryAndWage**
> \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWages getEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id)

Retrieves a specific salary and wages record for a specific employee using a unique salary and wage id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$salary_and_wages_id = 3fa85f64-5717-4562-b3fc-2c963f66afa6; // string | Id for single pay template earnings object

try {
    $result = $apiInstance->getEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeSalaryAndWage: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **salary_and_wages_id** | [**string**](../Model/.md)| Id for single pay template earnings object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWages**](../Model/SalaryAndWages.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeSalaryAndWages**
> \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWages getEmployeeSalaryAndWages($xero_tenant_id, $employee_id, $page)

Retrieves a specific employee's salary and wages by using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getEmployeeSalaryAndWages($xero_tenant_id, $employee_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeSalaryAndWages: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWages**](../Model/SalaryAndWages.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeStatutoryLeaveBalances**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutoryLeaveBalanceObject getEmployeeStatutoryLeaveBalances($xero_tenant_id, $employee_id, $leave_type, $as_of_date)

Retrieves a specific employee's leave balances using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$leave_type = sick; // string | Filter by the type of statutory leave
$as_of_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | The date from which to calculate balance remaining. If not specified, current date UTC is used.

try {
    $result = $apiInstance->getEmployeeStatutoryLeaveBalances($xero_tenant_id, $employee_id, $leave_type, $as_of_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeStatutoryLeaveBalances: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **leave_type** | **string**| Filter by the type of statutory leave | [optional]
 **as_of_date** | **\DateTime**| The date from which to calculate balance remaining. If not specified, current date UTC is used. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutoryLeaveBalanceObject**](../Model/EmployeeStatutoryLeaveBalanceObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeStatutorySickLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeaveObject getEmployeeStatutorySickLeave($xero_tenant_id, $statutory_sick_leave_id)

Retrieves a statutory sick leave for an employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$statutory_sick_leave_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Statutory sick leave id for single object

try {
    $result = $apiInstance->getEmployeeStatutorySickLeave($xero_tenant_id, $statutory_sick_leave_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeStatutorySickLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **statutory_sick_leave_id** | [**string**](../Model/.md)| Statutory sick leave id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutorySickLeaveObject**](../Model/EmployeeStatutorySickLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployeeTax**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeTaxObject getEmployeeTax($xero_tenant_id, $employee_id)

Retrieves tax records for a specific employee using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object

try {
    $result = $apiInstance->getEmployeeTax($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployeeTax: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeTaxObject**](../Model/EmployeeTaxObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployees**
> \XeroAPI\XeroPHP\Models\PayrollUk\Employees getEmployees($xero_tenant_id, $filter, $page)

Retrieves employees

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$filter = firstName==John,lastName==Smith; // string | Filter by first name and/or lastname
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getEmployees($xero_tenant_id, $filter, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getEmployees: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **filter** | **string**| Filter by first name and/or lastname | [optional]
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Employees**](../Model/Employees.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getLeaveType**
> \XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypeObject getLeaveType($xero_tenant_id, $leave_type_id)

Retrieves a specific leave type by using a unique leave type ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$leave_type_id = 'leave_type_id_example'; // string | Identifier for the leave type

try {
    $result = $apiInstance->getLeaveType($xero_tenant_id, $leave_type_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getLeaveType: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **leave_type_id** | [**string**](../Model/.md)| Identifier for the leave type |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypeObject**](../Model/LeaveTypeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getLeaveTypes**
> \XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypes getLeaveTypes($xero_tenant_id, $page, $active_only)

Retrieves leave types

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.
$active_only = True; // bool | Filters leave types by active status. By default the API returns all leave types.

try {
    $result = $apiInstance->getLeaveTypes($xero_tenant_id, $page, $active_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getLeaveTypes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]
 **active_only** | **bool**| Filters leave types by active status. By default the API returns all leave types. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\LeaveTypes**](../Model/LeaveTypes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayRun**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRunObject getPayRun($xero_tenant_id, $pay_run_id)

Retrieves a specific pay run by using a unique pay run ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$pay_run_id = 'pay_run_id_example'; // string | Identifier for the pay run

try {
    $result = $apiInstance->getPayRun($xero_tenant_id, $pay_run_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPayRun: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **pay_run_id** | [**string**](../Model/.md)| Identifier for the pay run |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunObject**](../Model/PayRunObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayRunCalendar**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendarObject getPayRunCalendar($xero_tenant_id, $pay_run_calendar_id)

Retrieves a specific payrun calendar by using a unique payrun calendar ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$pay_run_calendar_id = 'pay_run_calendar_id_example'; // string | Identifier for the payrun calendars

try {
    $result = $apiInstance->getPayRunCalendar($xero_tenant_id, $pay_run_calendar_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPayRunCalendar: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **pay_run_calendar_id** | [**string**](../Model/.md)| Identifier for the payrun calendars |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendarObject**](../Model/PayRunCalendarObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayRunCalendars**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendars getPayRunCalendars($xero_tenant_id, $page)

Retrieves payrun calendars

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getPayRunCalendars($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPayRunCalendars: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunCalendars**](../Model/PayRunCalendars.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayRuns**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRuns getPayRuns($xero_tenant_id, $page, $status)

Retrieves pay runs

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.
$status = 'status_example'; // string | By default get payruns will return all the payruns for an organization. You can add GET https://api.xero.com/payroll.xro/2.0/payRuns?statu={PayRunStatus} to filter the payruns by status.

try {
    $result = $apiInstance->getPayRuns($xero_tenant_id, $page, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPayRuns: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]
 **status** | **string**| By default get payruns will return all the payruns for an organization. You can add GET https://api.xero.com/payroll.xro/2.0/payRuns?statu&#x3D;{PayRunStatus} to filter the payruns by status. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRuns**](../Model/PayRuns.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPaySlip**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayslipObject getPaySlip($xero_tenant_id, $payslip_id)

Retrieves a specific payslip by using a unique payslip ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$payslip_id = 'payslip_id_example'; // string | Identifier for the payslip

try {
    $result = $apiInstance->getPaySlip($xero_tenant_id, $payslip_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPaySlip: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payslip_id** | [**string**](../Model/.md)| Identifier for the payslip |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayslipObject**](../Model/PayslipObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPaySlips**
> \XeroAPI\XeroPHP\Models\PayrollUk\Payslips getPaySlips($xero_tenant_id, $pay_run_id, $page)

Retrieves payslips

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$pay_run_id = 'pay_run_id_example'; // string | PayrunID which specifies the containing payrun of payslips to retrieve. By default, the API does not group payslips by payrun.
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getPaySlips($xero_tenant_id, $pay_run_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getPaySlips: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **pay_run_id** | [**string**](../Model/.md)| PayrunID which specifies the containing payrun of payslips to retrieve. By default, the API does not group payslips by payrun. |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Payslips**](../Model/Payslips.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReimbursement**
> \XeroAPI\XeroPHP\Models\PayrollUk\ReimbursementObject getReimbursement($xero_tenant_id, $reimbursement_id)

Retrieves a specific reimbursement by using a unique reimbursement id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$reimbursement_id = 'reimbursement_id_example'; // string | Identifier for the reimbursement

try {
    $result = $apiInstance->getReimbursement($xero_tenant_id, $reimbursement_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getReimbursement: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **reimbursement_id** | [**string**](../Model/.md)| Identifier for the reimbursement |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\ReimbursementObject**](../Model/ReimbursementObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReimbursements**
> \XeroAPI\XeroPHP\Models\PayrollUk\Reimbursements getReimbursements($xero_tenant_id, $page)

Retrieves reimbursements

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.

try {
    $result = $apiInstance->getReimbursements($xero_tenant_id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getReimbursements: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Reimbursements**](../Model/Reimbursements.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getSettings**
> \XeroAPI\XeroPHP\Models\PayrollUk\Settings getSettings($xero_tenant_id)

Retrieves payroll settings

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getSettings($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getSettings: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Settings**](../Model/Settings.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getStatutoryLeaveSummary**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutoryLeavesSummaries getStatutoryLeaveSummary($xero_tenant_id, $employee_id, $active_only)

Retrieves a specific employee's summary of statutory leaves using a unique employee ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$active_only = True; // bool | Filter response with leaves that are currently active or yet to be taken. If not specified, all leaves (past, current, and future scheduled) are returned

try {
    $result = $apiInstance->getStatutoryLeaveSummary($xero_tenant_id, $employee_id, $active_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getStatutoryLeaveSummary: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **active_only** | **bool**| Filter response with leaves that are currently active or yet to be taken. If not specified, all leaves (past, current, and future scheduled) are returned | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeStatutoryLeavesSummaries**](../Model/EmployeeStatutoryLeavesSummaries.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTimesheet**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject getTimesheet($xero_tenant_id, $timesheet_id)

Retrieve a specific timesheet by using a unique timesheet ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet

try {
    $result = $apiInstance->getTimesheet($xero_tenant_id, $timesheet_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getTimesheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject**](../Model/TimesheetObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTimesheets**
> \XeroAPI\XeroPHP\Models\PayrollUk\Timesheets getTimesheets($xero_tenant_id, $page, $filter)

Retrieves timesheets

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 56; // int | Page number which specifies the set of records to retrieve. By default the number of the records per set is 100.
$filter = employeeId==00000000-0000-0000-0000-000000000000,payrollCalendarId==00000000-0000-0000-0000-000000000000; // string | Filter by first name and/or lastname

try {
    $result = $apiInstance->getTimesheets($xero_tenant_id, $page, $filter);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getTimesheets: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Page number which specifies the set of records to retrieve. By default the number of the records per set is 100. | [optional]
 **filter** | **string**| Filter by first name and/or lastname | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\Timesheets**](../Model/Timesheets.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTrackingCategories**
> \XeroAPI\XeroPHP\Models\PayrollUk\TrackingCategories getTrackingCategories($xero_tenant_id)

Retrieves tracking categories

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getTrackingCategories($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->getTrackingCategories: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **revertTimesheet**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject revertTimesheet($xero_tenant_id, $timesheet_id)

Reverts a specific timesheet to draft

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet

try {
    $result = $apiInstance->revertTimesheet($xero_tenant_id, $timesheet_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->revertTimesheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetObject**](../Model/TimesheetObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateEmployee**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject updateEmployee($xero_tenant_id, $employee_id, $employee)

Updates a specific employee's detail

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employee = { "title":"Mr", "firstName":"Mike", "lastName":"Johnllsbkrhwopson", "dateOfBirth":"1999-01-01", "address":{ "addressLine1":"101 Green St", "city":"San Francisco", "postCode":"6TGR4F", "country":"UK" }, "email":"84044@starkindustries.com", "gender":"M" }; // \XeroAPI\XeroPHP\Models\PayrollUk\Employee | 

try {
    $result = $apiInstance->updateEmployee($xero_tenant_id, $employee_id, $employee);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateEmployee: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employee** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Employee**](../Model/Employee.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeObject**](../Model/EmployeeObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateEmployeeEarningsTemplate**
> \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplateObject updateEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $pay_template_earning_id, $earnings_template)

Updates a specific employee's earnings template records

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$pay_template_earning_id = 3fa85f64-5717-4562-b3fc-2c963f66afa6; // string | Id for single pay template earnings object
$earnings_template = { "ratePerUnit": 30, "numberOfUnits": 4, "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27" }; // \XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate | 

try {
    $result = $apiInstance->updateEmployeeEarningsTemplate($xero_tenant_id, $employee_id, $pay_template_earning_id, $earnings_template);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateEmployeeEarningsTemplate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **pay_template_earning_id** | [**string**](../Model/.md)| Id for single pay template earnings object |
 **earnings_template** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplate**](../Model/EarningsTemplate.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EarningsTemplateObject**](../Model/EarningsTemplateObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateEmployeeLeave**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject updateEmployeeLeave($xero_tenant_id, $employee_id, $leave_id, $employee_leave)

Updates a specific employee's leave records

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$leave_id = c4be24e5-e840-4c92-9eaa-2d86cd596314; // string | Leave id for single object
$employee_leave = { "leaveTypeID": "ed08dffe-788e-4b24-9630-f0fa2f4d164c", "description": "Creating a Description", "startDate": "2020-04-24", "endDate": "2020-04-26", "periods": [ { "periodStartDate": "2020-04-20", "periodEndDate": "2020-04-26", "numberOfUnits": 1, "periodStatus": "Approved" } ] }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeave | 

try {
    $result = $apiInstance->updateEmployeeLeave($xero_tenant_id, $employee_id, $leave_id, $employee_leave);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateEmployeeLeave: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **leave_id** | [**string**](../Model/.md)| Leave id for single object |
 **employee_leave** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeave**](../Model/EmployeeLeave.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeaveObject**](../Model/EmployeeLeaveObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateEmployeeOpeningBalances**
> \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject updateEmployeeOpeningBalances($xero_tenant_id, $employee_id, $employee_opening_balances)

Updates a specific employee's opening balances

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$employee_opening_balances = { "statutoryAdoptionPay": 20, "statutoryMaternityPay": 20, "statutoryPaternityPay": 20, "statutorySharedParentalPay": 20, "statutorySickPay": 20, "priorEmployeeNumber": 20 }; // \XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalances | 

try {
    $result = $apiInstance->updateEmployeeOpeningBalances($xero_tenant_id, $employee_id, $employee_opening_balances);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateEmployeeOpeningBalances: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **employee_opening_balances** | [**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalances**](../Model/EmployeeOpeningBalances.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\EmployeeOpeningBalancesObject**](../Model/EmployeeOpeningBalancesObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateEmployeeSalaryAndWage**
> \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWageObject updateEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id, $salary_and_wage)

Updates salary and wages record for a specific employee

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$employee_id = 4ff1e5cc-9835-40d5-bb18-09fdb118db9c; // string | Employee id for single object
$salary_and_wages_id = 3fa85f64-5717-4562-b3fc-2c963f66afa6; // string | Id for single pay template earnings object
$salary_and_wage = { "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnitsPerWeek": 3, "ratePerUnit": 11, "effectiveFrom": "2020-05-15", "annualSalary": 101, "status": "ACTIVE", "paymentType": "Salary" }; // \XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWage | 

try {
    $result = $apiInstance->updateEmployeeSalaryAndWage($xero_tenant_id, $employee_id, $salary_and_wages_id, $salary_and_wage);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateEmployeeSalaryAndWage: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Employee id for single object |
 **salary_and_wages_id** | [**string**](../Model/.md)| Id for single pay template earnings object |
 **salary_and_wage** | [**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWage**](../Model/SalaryAndWage.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWageObject**](../Model/SalaryAndWageObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updatePayRun**
> \XeroAPI\XeroPHP\Models\PayrollUk\PayRunObject updatePayRun($xero_tenant_id, $pay_run_id, $pay_run)

Updates a specific pay run

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$pay_run_id = 'pay_run_id_example'; // string | Identifier for the pay run
$pay_run = { "paymentDate": "2020-05-01" }; // \XeroAPI\XeroPHP\Models\PayrollUk\PayRun | 

try {
    $result = $apiInstance->updatePayRun($xero_tenant_id, $pay_run_id, $pay_run);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updatePayRun: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **pay_run_id** | [**string**](../Model/.md)| Identifier for the pay run |
 **pay_run** | [**\XeroAPI\XeroPHP\Models\PayrollUk\PayRun**](../Model/PayRun.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\PayRunObject**](../Model/PayRunObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTimesheetLine**
> \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLineObject updateTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line_id, $timesheet_line)

Updates a specific timesheet line for a specific timesheet

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\PayrollUkApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$timesheet_id = 'timesheet_id_example'; // string | Identifier for the timesheet
$timesheet_line_id = 'timesheet_line_id_example'; // string | Identifier for the timesheet line
$timesheet_line = { "date": "2020-04-14", "earningsRateID": "87f5b43a-cf51-4b74-92de-94c819e82d27", "numberOfUnits": 2 }; // \XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine | 

try {
    $result = $apiInstance->updateTimesheetLine($xero_tenant_id, $timesheet_id, $timesheet_line_id, $timesheet_line);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PayrollUkApi->updateTimesheetLine: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **timesheet_id** | [**string**](../Model/.md)| Identifier for the timesheet |
 **timesheet_line_id** | [**string**](../Model/.md)| Identifier for the timesheet line |
 **timesheet_line** | [**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLine**](../Model/TimesheetLine.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\PayrollUk\TimesheetLineObject**](../Model/TimesheetLineObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

