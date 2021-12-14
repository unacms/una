# xero-php-oauth2

## Project API Documentation

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

  $projectApi = new XeroAPI\XeroPHP\Api\ProjectApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  // XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate | Request of type ProjectCreateOrUpdate
  $projectCreateOrUpdate = new XeroAPI\XeroPHP\Models\Project\ProjectCreateOrUpdate;
  $projectCreateOrUpdate->setContactId($contactId)
	->setName("New Fence")
	->setDeadlineUtc(new DateTime('2019-12-10T12:59:59Z'))
	->setEstimateAmount(199.00);
	
  try {
      $result = $projectApi->createProject($xeroTenantId,$projectCreateOrUpdate); 	
      print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling projectApi->createProject: ', $e->getMessage(), PHP_EOL;
  }

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com/projects.xro/2.0*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ProjectApi* | [**createProject**](Api/ProjectApi.md#createproject) | **POST** /projects | create one or more new projects
*ProjectApi* | [**createTimeEntry**](Api/ProjectApi.md#createtimeentry) | **POST** /projects/{projectId}/time | Allows you to create a task
*ProjectApi* | [**deleteTimeEntry**](Api/ProjectApi.md#deletetimeentry) | **DELETE** /projects/{projectId}/time/{timeEntryId} | Allows you to delete a time entry
*ProjectApi* | [**getProject**](Api/ProjectApi.md#getproject) | **GET** /projects/{projectId} | Allows you to retrieve a single project
*ProjectApi* | [**getProjectUsers**](Api/ProjectApi.md#getprojectusers) | **GET** /projectsusers | list all project users
*ProjectApi* | [**getProjects**](Api/ProjectApi.md#getprojects) | **GET** /projects | list all projects
*ProjectApi* | [**getTask**](Api/ProjectApi.md#gettask) | **GET** /projects/{projectId}/tasks/{taskId} | Allows you to retrieve a single project
*ProjectApi* | [**getTasks**](Api/ProjectApi.md#gettasks) | **GET** /projects/{projectId}/tasks | Allows you to retrieve a single project
*ProjectApi* | [**getTimeEntries**](Api/ProjectApi.md#gettimeentries) | **GET** /projects/{projectId}/time | Allows you to retrieve the time entries associated with a specific project
*ProjectApi* | [**getTimeEntry**](Api/ProjectApi.md#gettimeentry) | **GET** /projects/{projectId}/time/{timeEntryId} | Allows you to get a single time entry in a project
*ProjectApi* | [**patchProject**](Api/ProjectApi.md#patchproject) | **PATCH** /projects/{projectId} | creates a project for the specified contact
*ProjectApi* | [**updateProject**](Api/ProjectApi.md#updateproject) | **PUT** /projects/{projectId} | update a specific project
*ProjectApi* | [**updateTimeEntry**](Api/ProjectApi.md#updatetimeentry) | **PUT** /projects/{projectId}/time/{timeEntryId} | Allows you to update time entry in a project


## Documentation For Models

 - [Amount](Model/Amount.md)
 - [ChargeType](Model/ChargeType.md)
 - [CurrencyCode](Model/CurrencyCode.md)
 - [Error](Model/Error.md)
 - [Pagination](Model/Pagination.md)
 - [Project](Model/Project.md)
 - [ProjectCreateOrUpdate](Model/ProjectCreateOrUpdate.md)
 - [ProjectPatch](Model/ProjectPatch.md)
 - [ProjectStatus](Model/ProjectStatus.md)
 - [ProjectUser](Model/ProjectUser.md)
 - [ProjectUsers](Model/ProjectUsers.md)
 - [Projects](Model/Projects.md)
 - [Task](Model/Task.md)
 - [TaskCreateOrUpdate](Model/TaskCreateOrUpdate.md)
 - [Tasks](Model/Tasks.md)
 - [TimeEntries](Model/TimeEntries.md)
 - [TimeEntry](Model/TimeEntry.md)
 - [TimeEntryCreateOrUpdate](Model/TimeEntryCreateOrUpdate.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **email**: Grant read-only access to your email
 - **openid**: Grant read-only access to your open id
 - **profile**: your profile information
 - **projects**: Grant read-write access to projects
 - **projects.read**: Grant read-only access to projects


## Author

api@xero.com


