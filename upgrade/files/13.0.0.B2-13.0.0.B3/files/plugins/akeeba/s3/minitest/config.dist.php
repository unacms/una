<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// Default Amazon S3 Access Key
define('DEFAULT_ACCESS_KEY', 'your s3 access key');
// Default Amazon S3 Secret Key
define('DEFAULT_SECRET_KEY', 'your secret key');
// Default region for the bucket
define('DEFAULT_REGION', 'us-east-1');
// Default bucket name
define('DEFAULT_BUCKET', 'example');
// Default signature method (v4 or v2)
define('DEFAULT_SIGNATURE', 'v4');
// Use Dualstack unless otherwise specified?
define('DEFAULT_DUALSTACK', false);
// Use legacy path access by default?
define('DEFAULT_PATH_ACCESS', false);
// Should I use SSL by default?
define('DEFAULT_SSL', true);
// Create the 2100 test files in the bucket?
define('CREATE_2100_FILES', true);

/**
 * Tests for standard key pairs allowing us to read, write and delete
 *
 * This is the main test suite
 */
$standardTests = [
	'BucketsList',
	'BucketLocation',
	'SmallFiles',
	'HeadObject',
	'SmallInlineFiles',
	'SignedURLs',
	'StorageClasses',
	'ListFiles',
	'BigFiles',
	'Multipart',
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
	'Global key, v4, DNS, single stack'  => [
		'configuration' => [
			'signature'   => 'v4',
			'dualstack'   => false,
			'path_access' => false,
		],
		'tests'         => $standardTests,
	],
	'Global key, v4, DNS, dual stack'    => [
		'configuration' => [
			'signature'   => 'v4',
			'dualstack'   => true,
			'path_access' => false,
		],
		'tests'         => $standardTests,
	],
	'Global key, v4, path, single stack' => [
		'configuration' => [
			'signature'   => 'v4',
			'dualstack'   => false,
			'path_access' => true,
		],
		'tests'         => $standardTests,
	],
	'Global key, v4, path, dual stack'   => [
		'configuration' => [
			'signature'   => 'v4',
			'dualstack'   => true,
			'path_access' => true,
		],
		'tests'         => $standardTests,
	],

	'Global key, v2, DNS, single stack' => [
		'configuration' => [
			'signature'   => 'v2',
			'dualstack'   => false,
			'path_access' => false,
		],
		'tests'         => $standardTests,
	],
	'Global key, v2, DNS, dual stack'   => [
		'configuration' => [
			'signature'   => 'v2',
			'dualstack'   => true,
			'path_access' => false,
		],
		'tests'         => $standardTests,
	],
];