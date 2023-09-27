# Testing notes

## Against Amazon S3 proper

This is the _canonical_ method for testing this library since Amazon S3 proper is the canonical provider of the S3 API (and not all of its quirks are fully documented, we might add). 

Copy `config.dist.php` to `config.php` and enter the connection information to your Amazon S3 or compatible service.

## Against [LocalStack](https://localstack.cloud)

This method is very useful for development.

Install LocalStack [as per their documentation](https://docs.localstack.cloud/getting-started/installation/).

You will also need to install [`awslocal`](https://github.com/localstack/awscli-local) like so:
```php
pip install awscli
pip install awscli-local
```

Start LocalStack e.g. `localstack start -d`

Create a new bucket called `test` i.e. `awslocal s3 mk s3://test`

Copy `config.dist.php` to `config.php` and make the following changes:
```php
    define('DEFAULT_ENDPOINT', 'localhost.localstack.cloud:4566');
    define('DEFAULT_ACCESS_KEY', 'ANYRANDOMSTRINGWILLDO');
    define('DEFAULT_SECRET_KEY', 'ThisIsAlwaysIgnoredByLocalStack');
    define('DEFAULT_REGION', 'us-east-1');
    define('DEFAULT_BUCKET', 'test');
    define('DEFAULT_SIGNATURE', 'v4');
    define('DEFAULT_PATH_ACCESS', true);
```

Note that single- and dualstack tests result in the same URLs for all S3-compatible services, including LocalStack. These tests are essentially duplicates in this use case.

## Against Wasabi

Wasabi nominally supports v4 signatures, but their implementation is actually _non-canonical_, as they only read the date from the optional `x-amz-date` header, without falling back to the standard HTTP `Date` header. We have added a workaround for this behaviour which necessitates testing with it.

Just like with Amazon S3 proper, copy `config.dist.php` to `config.php` and enter the connection information to your Wasabi storage. You will also need to set up the custom endpoint like so:
```php
define('DEFAULT_ENDPOINT', 's3.eu-central-2.wasabisys.com');
```

**IMPORTANT!** The above endpoint will be different, depending on which region you've created your bucket in. The example above assumes the `eu-central-2` region. If you use the wrong region the tests _will_ fail! 

## Against Synology C2

Synology C2 is an S3-“compatible” storage service. It is not very “compatible” though, since they implemented Amazon's documentation of the v4 signatures instead of how the v4 signatures work in the real world (yeah, there's a very big difference). While Amazon S3 _in reality_ expects all dates to be formatted as per RFC1123, they document that they expect them to be formatted as per “ISO 8601” and they give their _completely wrong_ interpretation of what the “ISO 8601” format is. Synology did not catch that discrepancy, and they only expect the wrongly formatted dates which is totally NOT what S3 itself expects. Luckily, most third party implementations expect either format because they've caught the discrepancy between documentation and reality, therefore making it possible for us to come up with a viable workaround.

And that's why we need to test with C2 as well, folks.

Copy `config.dist.php` to `config.php` and enter the connection information to your Synology S3 service.

It is very important to note two things:
```php
define('DEFAULT_ENDPOINT', 'eu-002.s3.synologyc2.net');
define('DEFAULT_REGION', 'eu-002');
```
The endpoint URL is given in the Synology C2 Object Manager, next to each bucket. Note the part before `.s3.`. This is the **region** you need to use with v4 signatures. They do not document this anywhere.