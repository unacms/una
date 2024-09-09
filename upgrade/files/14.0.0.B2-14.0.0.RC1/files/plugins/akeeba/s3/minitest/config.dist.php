<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2024 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// Create the 2100 test files in the bucket?
define('CREATE_2100_FILES', true);

/**
 * Configure the connection options for S3 and S3-compatible services here. Use them in the $testConfigurations array.
 */
$serviceConfigurations = [
	's3-v4'         => [
		'access'      => 'AK0123456789BCDEFGHI',
		'secret'      => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
		'region'      => 'eu-west-1',
		'bucket'      => 'mybucket',
		'signature'   => 'v4',
		'dualstack'   => false,
		'path_access' => false,
		'ssl'         => false,
		'endpoint'    => null,
	],
	's3-v2'         => [
		'access'      => 'AK0123456789BCDEFGHI',
		'secret'      => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
		'region'      => 'eu-west-1',
		'bucket'      => 'mybucket',
		'signature'   => 'v2',
		'dualstack'   => false,
		'path_access' => false,
		'ssl'         => false,
		'endpoint'    => null,
	],
	'localstack-v4' => [
		'access'      => 'KYLOREN',
		'secret'      => 'BenSolo',
		'region'      => 'us-east-1',
		'bucket'      => 'test',
		'signature'   => 'v4',
		'dualstack'   => false,
		'path_access' => true,
		'ssl'         => false,
		'endpoint'    => 'localhost.localstack.cloud:4566',
	],
	'localstack-v2' => [
		'access'      => 'KYLOREN',
		'secret'      => 'BenSolo',
		'region'      => 'us-east-1',
		'bucket'      => 'test',
		'signature'   => 'v2',
		'dualstack'   => false,
		'path_access' => true,
		'ssl'         => false,
		'endpoint'    => 'localhost.localstack.cloud:4566',
	],
];

/**
 * Test EVERYTHING.
 */
$allTheTests = [
	'BucketsList',
	'BucketLocation',
	'HeadObject',
	'ListFiles',
	'SmallFiles',
	'SmallInlineFiles',
	'SmallFilesNoDelete',
	'SmallFilesOnlyUpload',
	'SmallInlineFilesNoDelete',
	'SmallInlineFilesOnlyUpload',
	'SmallInlineXMLFiles',
	'BigFiles',
	'Multipart',
	'StorageClasses',
	'SignedURLs',
];

if (CREATE_2100_FILES)
{
	$allTheTests[] = 'ListThousandsOfFiles';
}

/**
 * Tests for standard key pairs allowing us to read, write and delete
 *
 * This is the main test suite
 */
$standardTests = [
	'BucketsList',
	'BucketLocation',
	'HeadObject',
	'ListFiles',
	'SmallFiles',
	'SmallInlineFiles',
	'SmallInlineXMLFiles',
	'BigFiles',
	'Multipart',
	'StorageClasses',
	'SignedURLs',
];

/**
 * Tests for key pairs or buckets which do NOT allow us to delete, but DO allow us to write and read data
 *
 * Example: archival buckets
 */
$noDeleteTests = [
	'SmallFilesNoDelete',
	'SmallInlineFilesNoDelete',
];

/**
 * Tests for key pairs which do NOT allow us to read, but DO allow us to write/delete
 *
 * Example: write-only key pairs per my documentation information from 2011 :)
 */
$writeOnlyTests = [
	'SmallFilesOnlyUpload',
	'SmallInlineFilesOnlyUpload',
];

/**
 * These are the individual test configurations.
 *
 * Each configuration consists of two keys:
 *
 * * **configuration**  Overrides to the default configuration.
 * * **tests**          The names of the test classes to execute. Use the format ['classname', 'method'] to execute
 *                      specific test methods only.
 */
$testConfigurations = [
// Format of each
//	'Description of this configuration' => array(
//		'configuration' => array(
//			// You can skip one or more keys. The defaults will be used.
//			'access' => 'a different access key',
//			'secret' => 'a different secret key',
//			'region' => 'eu-west-1',
//			'bucket' => 'different_example',
//			'signature' => 'v2',
//			'dualstack' => true,
//			'path_access' => true,
//			'ssl' => true,
//          // Only if you want to use a custom, non-Amazon endpoint
//          'endpoint' => null,
//		),
//		'tests' => array(
//          // Use a start to run all tests
//			'*',
//          // Alternatively you can define single test classes:
//			'SmallFiles',
//          // ..or specific tests:
//			array('SmallFiles', 'upload10KbRoot'),
//		)
//	),
	/**/

	/**
	 * These are the standard tests we run for each region and key pair.
	 *
	 * For all available regions please consult https://docs.aws.amazon.com/general/latest/gr/s3.html
	 *
	 * It is recommended to run against the following regions:
	 * - eu-east-1     The original Amazon S3 region, it often has special meaning in APIs.
	 * - eu-west-1     Ireland. The original EU region for S3, as a test for the non-default region.
	 * - eu-central-1  Frankfurt. This region –like all newer regions– only allows v4 signatures!
	 * - cn-north-1    Beijing, China. Requires running it from inside China.
	 * - NON-AMAZON    A custom endpoint for a third party, S3-compatible API. Ideally one for v2 and one for v4.
	 *
	 * Further to that test the following:
	 * - Write-only, bucket-restricted keys
	 * - Read/write, bucket-restricted keys
	 * - Buckets with dots
	 * - Buckets with uppercase letters
	 * - Buckets with international letters
	 * - Access from within EC2
	 */

	// Amazon S3, v2 signatures
	'S3, v2, subdomain, single stack'  => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v2'],
			[
				'dualstack'   => false,
				'path_access' => false,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v2, subdomain, dual stack'    => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v2'],
			[
				'dualstack'   => true,
				'path_access' => false,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v2, path, single stack'  => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v2'],
			[
				'dualstack'   => false,
				'path_access' => true,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v2, path, dual stack'    => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v2'],
			[
				'dualstack'   => true,
				'path_access' => true,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],

	// Amazon S3, v4 signatures
	'S3, v4, subdomain, single stack'  => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v4'],
			[
				'dualstack'   => false,
				'path_access' => false,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v4, subdomain, dual stack'    => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v4'],
			[
				'dualstack'   => true,
				'path_access' => false,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v4, path, single stack'  => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v4'],
			[
				'dualstack'   => false,
				'path_access' => true,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],
	'S3, v4, path, dual stack'    => [
		'configuration' => array_merge(
			$serviceConfigurations['s3-v4'],
			[
				'dualstack'   => true,
				'path_access' => true,
			]
		),
		'tests'         => $standardTests,
		'skip'          => false,
	],

	// LocalStack
	'LocalStack, V2 (always path access, single stack)' => [
		'configuration' => $serviceConfigurations['localstack-v2'],
		'tests'         => $allTheTests,
		'skip'          => false,
	],

	'LocalStack, V4 (always path access, single stack)' => [
		'configuration' => $serviceConfigurations['localstack-v4'],
		'tests'         => $allTheTests,
		'skip'          => false,
	],

	/**
	 * In the real config file we also have tests running on:
	 *
	 * - Wasabi, v2, path-style access.
	 * - Wasabi, v4, path-style access.
	 * - Synology C2, subdomain access.
	 *
	 * See [[NOTES.md]] for more information. If you have another environment you think we should test with please
	 * update NOTES.md and make a pull request, along with the reasoning behind it.
	 *
	 * There are known failures for some cases, notably LocalStack v4 (something is amiss?) and C2 (necessary flag is
	 * not yet supported by the minitest framework).
	 */
];