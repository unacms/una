# xero-php-oauth2

## File API Documentation

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

  $fileApi = new XeroAPI\XeroPHP\Api\FilesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  $name = "xerodev.jpg";
  $filename = "xero-dev.jpg";
  $file = "./xero-dev.jpg";
  $handle = fopen($file, "r");
  $contents = fread($handle, filesize($file));
  fclose($handle);
  
  try {
    $results = $fileApi->uploadFile($xeroTenantId,null,$contents,$name, $filename, null);
    print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling fileApi->uploadFile: ', $e->getMessage(), PHP_EOL;
  }

  try {
    $fileList = $fileApi->getFiles($xeroTenantId); 
    print_r($fileList);
  } catch (Exception $e) {
      echo 'Exception when calling fileApi->getFiles: ', $e->getMessage(), PHP_EOL;
  }
  
?>
```

## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com/files.xro/1.0*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*FilesApi* | [**createFileAssociation**](Api/FilesApi.md#createfileassociation) | **POST** /Files/{FileId}/Associations | create a new association
*FilesApi* | [**createFolder**](Api/FilesApi.md#createfolder) | **POST** /Folders | create a new folder
*FilesApi* | [**deleteFile**](Api/FilesApi.md#deletefile) | **DELETE** /Files/{FileId} | delete a file
*FilesApi* | [**deleteFileAssociation**](Api/FilesApi.md#deletefileassociation) | **DELETE** /Files/{FileId}/Associations/{ObjectId} | create a new association
*FilesApi* | [**deleteFolder**](Api/FilesApi.md#deletefolder) | **DELETE** /Folders/{FolderId} | delete a folder
*FilesApi* | [**getAssociationsByObject**](Api/FilesApi.md#getassociationsbyobject) | **GET** /Associations/{ObjectId} | searches files
*FilesApi* | [**getFile**](Api/FilesApi.md#getfile) | **GET** /Files/{FileId} | searches for file by unique id
*FilesApi* | [**getFileAssociations**](Api/FilesApi.md#getfileassociations) | **GET** /Files/{FileId}/Associations | searches files
*FilesApi* | [**getFileContent**](Api/FilesApi.md#getfilecontent) | **GET** /Files/{FileId}/Content | searches files to retrieve the data
*FilesApi* | [**getFiles**](Api/FilesApi.md#getfiles) | **GET** /Files | searches files
*FilesApi* | [**getFolder**](Api/FilesApi.md#getfolder) | **GET** /Folders/{FolderId} | searches specific folder by id
*FilesApi* | [**getFolders**](Api/FilesApi.md#getfolders) | **GET** /Folders | searches folder
*FilesApi* | [**getInbox**](Api/FilesApi.md#getinbox) | **GET** /Inbox | searches inbox folder
*FilesApi* | [**updateFile**](Api/FilesApi.md#updatefile) | **PUT** /Files/{FileId} | Update a file
*FilesApi* | [**updateFolder**](Api/FilesApi.md#updatefolder) | **PUT** /Folders/{FolderId} | update folder
*FilesApi* | [**uploadFile**](Api/FilesApi.md#uploadfile) | **POST** /Files | upload an File


## Documentation For Models

 - [Association](Model/Association.md)
 - [FileObject](Model/FileObject.md)
 - [FileResponse204](Model/FileResponse204.md)
 - [Files](Model/Files.md)
 - [Folder](Model/Folder.md)
 - [Folders](Model/Folders.md)
 - [InlineObject](Model/InlineObject.md)
 - [ObjectGroup](Model/ObjectGroup.md)
 - [ObjectType](Model/ObjectType.md)
 - [User](Model/User.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **email**: Grant read-only access to your email
 - **openid**: Grant read-only access to your open id
 - **profile**: your profile information
 - **files**: Grant read-write access to files and folders
 - **files.read**: Grant read-only access to files and folders


## Author

api@xero.com


