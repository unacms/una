# Testing notes

**⚠️ WARNING**: Running all tests across all services takes _hours_. It is recommended that you use the SignedURLs test as a quick validation tool across multiple services, then test against the `$standardTests` suite overnight.

## Against Amazon S3 proper

This is the _canonical_ method for testing this library since Amazon S3 proper is the canonical provider of the S3 API (and not all of its quirks are fully documented, we might add). 

Copy `config.dist.php` to `config.php` and enter the connection information to your Amazon S3 or compatible service.

## Against [LocalStack](https://localstack.cloud)

This method is very useful for development. It is the most faithful implementation of S3, but it does have some minor quirks not present in the real thing as a result of it running inside a Docker container.

Install LocalStack [per its documentation](https://docs.localstack.cloud/getting-started/installation/), or using its Docker Desktop Extension.

You will also need to install [`awslocal`](https://github.com/localstack/awscli-local) like so:
```php
pip install awscli
pip install awscli-local
```

Start LocalStack e.g. `localstack start -d` or via the Docker Desktop Extension.

Create a new bucket called `test` i.e. `awslocal s3 mb s3://test`

The `config.dist.php` already has a configuration for LocalStack. Yes, the access and secret key can be any random string.

## Against Wasabi

Wasabi nominally supports v4 signatures, but their implementation is actually _non-canonical_, as they only read the date from the optional `x-amz-date` header, without falling back to the standard HTTP `Date` header. We have changed the behaviour of the library to always go through the X-Amz-Date header as a result. Hence, the need to test with Wasabi.

The Wasabi configuration block looks like this:

```php
	'wasabi-v4' => [
		'access'      => 'THE_ACCESS_KEY',
		'secret'      => 'VERY_SECRET_MUCH_WOW',
		'region'      => 'eu-central-2',
		'bucket'      => 'bucketname',
		'signature'   => 'v4',
		'dualstack'   => false,
		'path_access' => true,
		'ssl'         => true,
		'endpoint'    => 's3.eu-central-2.wasabisys.com',
	],
	'wasabi-v2' => [
		'access'      => 'THE_ACCESS_KEY',
		'secret'      => 'VERY_SECRET_MUCH_WOW',
		'region'      => 'eu-central-2',
		'bucket'      => 'bucketname',
		'signature'   => 'v2',
		'dualstack'   => false,
		'path_access' => true,
		'ssl'         => true,
		'endpoint'    => 's3.eu-central-2.wasabisys.com',
	],

```

**❗Important**: The Endpoint and Region must match each other, and the region the bucket was crated in. In the example above, we have created a bucket in the `eu-central-2` region. If you use the wrong region and/or endpoint the tests _will_ fail! 

## Against Synology C2

Synology C2 is an S3-“compatible” storage service. It is not very “compatible” though, since they implemented Amazon's documentation of the v4 signatures instead of how the v4 signatures work in the real world (yeah, there's a very big difference). While Amazon S3 _in reality_ expects all dates to be formatted as per RFC1123, they document that they expect them to be formatted as per “ISO 8601”, and they give their _completely wrong_ interpretation of what the “ISO 8601” format is. Synology did not catch that discrepancy, and they only expect the wrongly formatted dates which is totally NOT what S3 itself expects. Luckily, most third party implementations expect either format because they've caught the discrepancy between documentation and reality, therefore making it possible for us to come up with a viable workaround.

And that's why we need to test with C2 as well, folks.

The C2 config block looks like this:

```php
	'c2' => [
		'access'      => 'THE_ACCESS_KEY',
		'secret'      => 'VERY_SECRET_MUCH_WOW',
		'region'      => 'eu-002',
		'bucket'      => 'bucketname',
		'signature'   => 'v4',
		'dualstack'   => false,
		'path_access' => false,
		'ssl'         => true,
		'endpoint'    => 'eu-002.s3.synologyc2.net',
	],

```

The endpoint URL is given in the Synology C2 Object Manager, next to each bucket. Note the part before `.s3.`. This is the **region** you need to use with v4 signatures. They do not document this anywhere.