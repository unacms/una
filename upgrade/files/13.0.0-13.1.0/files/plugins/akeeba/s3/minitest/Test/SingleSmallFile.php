<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\MiniTest\Test;

use Akeeba\S3\Connector;
use Akeeba\S3\Input;

/**
 * Upload, download and delete small files (under 1MB) using a string source
 *
 * @package Akeeba\MiniTest\Test
 */
class SingleSmallFile extends AbstractTest
{
	public static function upload(Connector $s3, array $options): bool
	{
		$uri        = 'test.txt';
		$sourceData = <<< TEXT
This is a small text file.
TEXT;


		// Upload the data. Throws exception if it fails.
		$bucket = $options['bucket'];
		$input  = Input::createFromData($sourceData);

		$s3->putObject($input, $bucket, $uri);

		$downloadedData = $s3->getObject($bucket, $uri);
		$result         = static::areStringsEqual($sourceData, $downloadedData);

		$s3->deleteObject($bucket, $uri);

		return $result ?? true;
	}
}