<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */


namespace Akeeba\MiniTest\Test;


use Akeeba\Engine\Postproc\Connector\S3v4\Connector;
use Akeeba\Engine\Postproc\Connector\S3v4\Exception\CannotDeleteFile;
use Akeeba\Engine\Postproc\Connector\S3v4\Exception\CannotGetFile;
use Akeeba\Engine\Postproc\Connector\S3v4\Input;

class HeadObject extends AbstractTest
{
	public static function testExistingFile(Connector $s3, array $options): bool
	{
		$uri = 'head_test.dat';

		// Randomize the name. Required for archive buckets where you cannot overwrite data.
		$dotPos = strrpos($uri, '.');
		$uri    = substr($uri, 0, $dotPos) . '.' . md5(microtime(false)) . substr($uri, $dotPos);

		// Create a file with random data
		$sourceFile = self::createFile(AbstractTest::TEN_KB);

		// Upload the file. Throws exception if it fails.
		$bucket = $options['bucket'];
		$input  = Input::createFromFile($sourceFile);

		$s3->putObject($input, $bucket, $uri);

		$headers = $s3->headObject($bucket, $uri);

		self::assert(isset($headers['size']), 'The returned headers do not contain the object size');
		self::assert($headers['size'] == AbstractTest::TEN_KB, 'The returned size does not match');

		// Remove the local files
		@unlink($sourceFile);

		// Delete the remote file. Throws exception if it fails.
		$s3->deleteObject($bucket, $uri);

		return true;
	}

	public static function testMissingFile(Connector $s3, array $options): bool
	{
		$bucket = $options['bucket'];

		try
		{
			$headers = $s3->headObject($bucket, md5(microtime(false)) . '_does_not_exist');
		}
		catch (CannotGetFile $e)
		{
			return true;
		}

		return false;
	}
}