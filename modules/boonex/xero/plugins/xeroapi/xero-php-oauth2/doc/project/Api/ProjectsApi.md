# XeroAPI\XeroPHP\ProjectsApi

All URIs are relative to *https://api.xero.com/projects.xro/2.0*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createProject**](ProjectsApi.md#createProject) | **POST** /projects | create one or more new projects
[**createTimeEntry**](ProjectsApi.md#createTimeEntry) | **POST** /projects/{projectId}/time | Allows you to create a task
[**deleteTimeEntry**](ProjectsApi.md#deleteTimeEntry) | **DELETE** /projects/{projectId}/time/{timeEntryId} | Allows you to delete a time entry
[**getProject**](ProjectsApi.md#getProject) | **GET** /projects/{projectId} | Allows you to retrieve a single project
[**getProjectUsers**](ProjectsApi.md#getProjectUsers) | **GET** /projectsusers | list all project users
[**getProjects**](ProjectsApi.md#getProjects) | **GET** /projects | list all projects
[**getTask**](ProjectsApi.md#getTask) | **GET** /projects/{projectId}/tasks/{taskId} | Allows you to retrieve a single project
[**getTasks**](ProjectsApi.md#getTasks) | **GET** /projects/{projectId}/tasks | Allows you to retrieve a single project
[**getTimeEntries**](ProjectsApi.md#getTimeEntries) | **GET** /projects/{projectId}/time | Allows you to retrieve the time entries associated with a specific project
[**getTimeEntry**](ProjectsApi.md#getTimeEntry) | **GET** /projects/{projectId}/time/{timeEntryId} | Allows you to get a single time entry in a project
[**patchProject**](ProjectsApi.md#patchProject) | **PATCH** /projects/{projectId} | creates a project for the specified contact
[**updateProject**](ProjectsApi.md#updateProject) | **PUT** /projects/{projectId} | update a specific project
[**updateTimeEntry**](ProjectsApi.md#updateTimeEntry) | **PUT** /projects/{projectId}/time/{timeEntryId} | Allows you to update time entry in a project


# **createProject**
> \XeroAPI\XeroPHP\Models\Project\Project createProject($xero_tenant_id, $project_create_or_update)

create one or more new projects

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_create_or_update = { "contactId":"00000000-0000-0000-000-000000000000", "name":"New Kitchen", "deadlineUtc":"2019-12-10T12:59:59Z", "estimateAmount":"99.99" }; // \XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate | Create a new project with ProjectCreateOrUpdate object

try {
    $result = $apiInstance->createProject($xero_tenant_id, $project_create_or_update);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->createProject: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_create_or_update** | [**\XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate**](../Model/ProjectCreateOrUpdate.md)| Create a new project with ProjectCreateOrUpdate object |

### Return type

[**\XeroAPI\XeroPHP\Models\Project\Project**](../Model/Project.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTimeEntry**
> \XeroAPI\XeroPHP\Models\Project\TimeEntry createTimeEntry($xero_tenant_id, $project_id, $time_entry_create_or_update)

Allows you to create a task

Allows you to create a specific task

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$time_entry_create_or_update = { "userId":"740add2a-a703-4b8a-a670-1093919c2040", "taskId":"7be77337-feec-4458-bb1b-dbaa5a4aafce", "dateUtc":"2020-02-26T15:00:00Z", "duration":30, "description":"My description" }; // \XeroAPI\XeroPHP\Models\Project\TimeEntryCreateOrUpdate | The time entry object you are creating

try {
    $result = $apiInstance->createTimeEntry($xero_tenant_id, $project_id, $time_entry_create_or_update);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->createTimeEntry: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **time_entry_create_or_update** | [**\XeroAPI\XeroPHP\Models\Project\TimeEntryCreateOrUpdate**](../Model/TimeEntryCreateOrUpdate.md)| The time entry object you are creating |

### Return type

[**\XeroAPI\XeroPHP\Models\Project\TimeEntry**](../Model/TimeEntry.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTimeEntry**
> deleteTimeEntry($xero_tenant_id, $project_id, $time_entry_id)

Allows you to delete a time entry

Allows you to delete a specific time entry

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$time_entry_id = 'time_entry_id_example'; // string | You can specify an individual task by appending the id to the endpoint

try {
    $apiInstance->deleteTimeEntry($xero_tenant_id, $project_id, $time_entry_id);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->deleteTimeEntry: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **time_entry_id** | [**string**](../Model/.md)| You can specify an individual task by appending the id to the endpoint |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getProject**
> \XeroAPI\XeroPHP\Models\Project\Project getProject($xero_tenant_id, $project_id)

Allows you to retrieve a single project

Allows you to retrieve a specific project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint

try {
    $result = $apiInstance->getProject($xero_tenant_id, $project_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getProject: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |

### Return type

[**\XeroAPI\XeroPHP\Models\Project\Project**](../Model/Project.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getProjectUsers**
> \XeroAPI\XeroPHP\Models\Project\ProjectUsers getProjectUsers($xero_tenant_id, $page, $page_size)

list all project users

Allows you to retrieve the users on a projects.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$page = 1; // int | set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0.
$page_size = 100; // int | Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500.

try {
    $result = $apiInstance->getProjectUsers($xero_tenant_id, $page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getProjectUsers: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0. | [optional] [default to 1]
 **page_size** | **int**| Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500. | [optional] [default to 50]

### Return type

[**\XeroAPI\XeroPHP\Models\Project\ProjectUsers**](../Model/ProjectUsers.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getProjects**
> \XeroAPI\XeroPHP\Models\Project\Projects getProjects($xero_tenant_id, $project_ids, $contact_id, $states, $page, $page_size)

list all projects

Allows you to retrieve, create and update projects.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_ids = array('project_ids_example'); // string[] | Search for all projects that match a comma separated list of projectIds
$contact_id = 'contact_id_example'; // string | Filter for projects for a specific contact
$states = 'states_example'; // string | Filter for projects in a particular state (INPROGRESS or CLOSED)
$page = 1; // int | set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0.
$page_size = 100; // int | Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500.

try {
    $result = $apiInstance->getProjects($xero_tenant_id, $project_ids, $contact_id, $states, $page, $page_size);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getProjects: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_ids** | [**string[]**](../Model/string.md)| Search for all projects that match a comma separated list of projectIds | [optional]
 **contact_id** | [**string**](../Model/.md)| Filter for projects for a specific contact | [optional]
 **states** | **string**| Filter for projects in a particular state (INPROGRESS or CLOSED) | [optional]
 **page** | **int**| set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0. | [optional] [default to 1]
 **page_size** | **int**| Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500. | [optional] [default to 50]

### Return type

[**\XeroAPI\XeroPHP\Models\Project\Projects**](../Model/Projects.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTask**
> \XeroAPI\XeroPHP\Models\Project\Task getTask($xero_tenant_id, $project_id, $task_id)

Allows you to retrieve a single project

Allows you to retrieve a specific project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$task_id = 'task_id_example'; // string | You can specify an individual task by appending the taskId to the endpoint, i.e. GET https://.../tasks/{taskId}

try {
    $result = $apiInstance->getTask($xero_tenant_id, $project_id, $task_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getTask: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **task_id** | [**string**](../Model/.md)| You can specify an individual task by appending the taskId to the endpoint, i.e. GET https://.../tasks/{taskId} |

### Return type

[**\XeroAPI\XeroPHP\Models\Project\Task**](../Model/Task.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTasks**
> \XeroAPI\XeroPHP\Models\Project\Tasks getTasks($xero_tenant_id, $project_id, $page, $page_size, $task_ids)

Allows you to retrieve a single project

Allows you to retrieve a specific project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$page = 1; // int | Set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0.
$page_size = 10; // int | Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500.
$task_ids = 'task_ids_example'; // string | taskIds Search for all tasks that match a comma separated list of taskIds, i.e. GET https://.../tasks?taskIds={taskId},{taskId}

try {
    $result = $apiInstance->getTasks($xero_tenant_id, $project_id, $page, $page_size, $task_ids);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getTasks: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **page** | **int**| Set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0. | [optional]
 **page_size** | **int**| Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500. | [optional]
 **task_ids** | **string**| taskIds Search for all tasks that match a comma separated list of taskIds, i.e. GET https://.../tasks?taskIds&#x3D;{taskId},{taskId} | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Project\Tasks**](../Model/Tasks.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTimeEntries**
> \XeroAPI\XeroPHP\Models\Project\TimeEntries getTimeEntries($xero_tenant_id, $project_id, $user_id, $task_id, $invoice_id, $contact_id, $page, $page_size, $states, $is_chargeable, $date_after_utc, $date_before_utc)

Allows you to retrieve the time entries associated with a specific project

Allows you to retrieve the time entries associated with a specific project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | Identifier of the project, that the task (which the time entry is logged against) belongs to.
$user_id = 'user_id_example'; // string | The xero user identifier of the person who logged time.
$task_id = 'task_id_example'; // string | Identifier of the task that time entry is logged against.
$invoice_id = 'invoice_id_example'; // string | Finds all time entries for this invoice.
$contact_id = 'contact_id_example'; // string | Finds all time entries for this contact identifier.
$page = 1; // int | Set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0.
$page_size = 10; // int | Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500.
$states = array('states_example'); // string[] | Comma-separated list of states to find. Will find all time entries that are in the status of whatever’s specified.
$is_chargeable = True; // bool | Finds all time entries which relate to tasks with the charge type `TIME` or `FIXED`.
$date_after_utc = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | ISO 8601 UTC date. Finds all time entries on or after this date filtered on the `dateUtc` field.
$date_before_utc = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | ISO 8601 UTC date. Finds all time entries on or before this date filtered on the `dateUtc` field.

try {
    $result = $apiInstance->getTimeEntries($xero_tenant_id, $project_id, $user_id, $task_id, $invoice_id, $contact_id, $page, $page_size, $states, $is_chargeable, $date_after_utc, $date_before_utc);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getTimeEntries: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| Identifier of the project, that the task (which the time entry is logged against) belongs to. |
 **user_id** | [**string**](../Model/.md)| The xero user identifier of the person who logged time. | [optional]
 **task_id** | [**string**](../Model/.md)| Identifier of the task that time entry is logged against. | [optional]
 **invoice_id** | [**string**](../Model/.md)| Finds all time entries for this invoice. | [optional]
 **contact_id** | [**string**](../Model/.md)| Finds all time entries for this contact identifier. | [optional]
 **page** | **int**| Set to 1 by default. The requested number of the page in paged response - Must be a number greater than 0. | [optional]
 **page_size** | **int**| Optional, it is set to 50 by default. The number of items to return per page in a paged response - Must be a number between 1 and 500. | [optional]
 **states** | [**string[]**](../Model/string.md)| Comma-separated list of states to find. Will find all time entries that are in the status of whatever’s specified. | [optional]
 **is_chargeable** | **bool**| Finds all time entries which relate to tasks with the charge type &#x60;TIME&#x60; or &#x60;FIXED&#x60;. | [optional]
 **date_after_utc** | **\DateTime**| ISO 8601 UTC date. Finds all time entries on or after this date filtered on the &#x60;dateUtc&#x60; field. | [optional]
 **date_before_utc** | **\DateTime**| ISO 8601 UTC date. Finds all time entries on or before this date filtered on the &#x60;dateUtc&#x60; field. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Project\TimeEntries**](../Model/TimeEntries.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTimeEntry**
> \XeroAPI\XeroPHP\Models\Project\TimeEntry getTimeEntry($xero_tenant_id, $project_id, $time_entry_id)

Allows you to get a single time entry in a project

Allows you to upget a single time entry in a project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$time_entry_id = 'time_entry_id_example'; // string | You can specify an individual time entry by appending the id to the endpoint

try {
    $result = $apiInstance->getTimeEntry($xero_tenant_id, $project_id, $time_entry_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->getTimeEntry: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **time_entry_id** | [**string**](../Model/.md)| You can specify an individual time entry by appending the id to the endpoint |

### Return type

[**\XeroAPI\XeroPHP\Models\Project\TimeEntry**](../Model/TimeEntry.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **patchProject**
> patchProject($xero_tenant_id, $project_id, $project_patch)

creates a project for the specified contact

Allows you to update a specific projects.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$project_patch = { "status": "INPROGRESS" }; // \XeroAPI\XeroPHP\Models\Project\ProjectPatch | Update the status of an existing Project

try {
    $apiInstance->patchProject($xero_tenant_id, $project_id, $project_patch);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->patchProject: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **project_patch** | [**\XeroAPI\XeroPHP\Models\Project\ProjectPatch**](../Model/ProjectPatch.md)| Update the status of an existing Project |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateProject**
> updateProject($xero_tenant_id, $project_id, $project_create_or_update)

update a specific project

Allows you to update a specific projects.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$project_create_or_update = { "name": "New Kitchen", "deadlineUtc": "2017-04-23T18:25:43.511Z", "estimateAmount": 99.99 }; // \XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate | Request of type ProjectCreateOrUpdate

try {
    $apiInstance->updateProject($xero_tenant_id, $project_id, $project_create_or_update);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->updateProject: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **project_create_or_update** | [**\XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate**](../Model/ProjectCreateOrUpdate.md)| Request of type ProjectCreateOrUpdate |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTimeEntry**
> updateTimeEntry($xero_tenant_id, $project_id, $time_entry_id, $time_entry_create_or_update)

Allows you to update time entry in a project

Allows you to update time entry in a project

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$apiInstance = new XeroAPI\XeroPHP\Api\ProjectsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$xero_tenant_id = 'xero_tenant_id_example'; // string | Xero identifier for Tenant
$project_id = 'project_id_example'; // string | You can specify an individual project by appending the projectId to the endpoint
$time_entry_id = 'time_entry_id_example'; // string | You can specify an individual time entry by appending the id to the endpoint
$time_entry_create_or_update = { "userId":"740add2a-a703-4b8a-a670-1093919c2040", "taskId":"7be77337-feec-4458-bb1b-dbaa5a4aafce", "dateUtc":"2020-02-27T15:00:00Z", "duration":45, "description":"My UPDATED description" }; // \XeroAPI\XeroPHP\Models\Project\TimeEntryCreateOrUpdate | The time entry object you are updating

try {
    $apiInstance->updateTimeEntry($xero_tenant_id, $project_id, $time_entry_id, $time_entry_create_or_update);
} catch (Exception $e) {
    echo 'Exception when calling ProjectsApi->updateTimeEntry: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **project_id** | [**string**](../Model/.md)| You can specify an individual project by appending the projectId to the endpoint |
 **time_entry_id** | [**string**](../Model/.md)| You can specify an individual time entry by appending the id to the endpoint |
 **time_entry_create_or_update** | [**\XeroAPI\XeroPHP\Models\Project\TimeEntryCreateOrUpdate**](../Model/TimeEntryCreateOrUpdate.md)| The time entry object you are updating |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

