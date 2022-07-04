<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\MiniTest\Test;


use Akeeba\Engine\Postproc\Connector\S3v4\Acl;
use Akeeba\Engine\Postproc\Connector\S3v4\Connector;
use Akeeba\Engine\Postproc\Connector\S3v4\Input;
use Akeeba\Engine\Postproc\Connector\S3v4\StorageClass;

class StorageClasses extends AbstractTest
{
	protected static $downloadAfter = true;

	protected static $deleteRemote = true;

	public static function uploadRRS(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::TEN_KB, 'rrs_test_10kb.dat', StorageClass::REDUCED_REDUNDANCY);
	}

	public static function uploadIntelligentTiering(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::TEN_KB, 'rrs_test_10kb.dat', StorageClass::INTELLIGENT_TIERING);
	}

	protected static function upload(Connector $s3, array $options, int $size, string $uri, string $storageClass = null)
	{
		// Randomize the name. Required for archive buckets where you cannot overwrite data.
		$dotPos = strrpos($uri, '.');
		$uri    = substr($uri, 0, $dotPos) . '.' . md5(microtime(false)) . substr($uri, $dotPos);

		// Create some random data to upload
		$sourceData = self::getRandomData($size);

		// Upload the data. Throws exception if it fails.
		$bucket = $options['bucket'];
		$input  = Input::createFromData($sourceData);

		// Get the headers
		$headers = [];
		StorageClass::setStorageClass($headers, $storageClass);

		$s3->putObject($input, $bucket, $uri, Acl::ACL_PRIVATE, $headers);

		// Tentatively accept that this method succeeded.
		$result = true;

		// Should I download the file and compare its contents with my random data?
		if (self::$downloadAfter)
		{
			$downloadedData = $s3->getObject($bucket, $uri);

			$result = self::areStringsEqual($sourceData, $downloadedData);
		}

		// Should I delete the remotely stored file?
		if (self::$deleteRemote)
		{
			// Delete the remote file. Throws exception if it fails.
			$s3->deleteObject($bucket, $uri);
		}

		return $result;
	}

}