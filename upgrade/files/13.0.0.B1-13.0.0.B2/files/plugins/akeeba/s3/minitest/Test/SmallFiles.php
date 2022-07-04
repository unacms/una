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
use Akeeba\Engine\Postproc\Connector\S3v4\Input;

/**
 * Upload, download and delete small files (under 1MB) using a file source
 *
 * @package Akeeba\MiniTest\Test
 */
class SmallFiles extends AbstractTest
{
	/**
	 * Should I download the file after uploading it to test for contents consistency?
	 *
	 * @var bool
	 */
	protected static $downloadAfter = true;

	/**
	 * Should I delete the uploaded file after the test case is done?
	 *
	 * @var bool
	 */
	protected static $deleteRemote = true;

	public static function upload10KbRoot(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::TEN_KB, 'root_10kb.dat');
	}

	public static function upload10KbRootGreek(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::TEN_KB, 'δοκιμή_10kb.dat');
	}

	public static function upload10KbFolderGreek(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::TEN_KB, 'ο_φάκελός_μου/δοκιμή_10kb.dat');
	}

	public static function upload600KbRoot(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::SIX_HUNDRED_KB, 'root_600kb.dat');
	}

	public static function upload10KbFolder(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::TEN_KB, 'my_folder/10kb.dat');
	}

	public static function upload600KbFolder(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, AbstractTest::SIX_HUNDRED_KB, 'my_folder/600kb.dat');
	}

	protected static function upload(Connector $s3, array $options, int $size, string $uri): bool
	{
		// Randomize the name. Required for archive buckets where you cannot overwrite data.
		$dotPos = strrpos($uri, '.');
		$uri    = substr($uri, 0, $dotPos) . '.' . md5(microtime(false)) . substr($uri, $dotPos);

		// Create a file with random data
		$sourceFile = self::createFile($size);

		// Upload the file. Throws exception if it fails.
		$bucket = $options['bucket'];
		$input  = Input::createFromFile($sourceFile);

		$s3->putObject($input, $bucket, $uri);

		// Tentatively accept that this method succeeded.
		$result = true;

		// Should I download the file and compare its contents?
		if (self::$downloadAfter)
		{
			// Donwload the data. Throws exception if it fails.
			$downloadedFile = tempnam(self::getTempFolder(), 'as3');
			$s3->getObject($bucket, $uri, $downloadedFile);

			// Compare the file contents.
			$result = self::areFilesEqual($sourceFile, $downloadedFile);
		}

		// Remove the local files
		@unlink($sourceFile);
		@unlink($downloadedFile);

		// Should I delete the remotely stored file?
		if (self::$deleteRemote)
		{
			// Delete the remote file. Throws exception if it fails.
			$s3->deleteObject($bucket, $uri);
		}

		return $result;
	}
}