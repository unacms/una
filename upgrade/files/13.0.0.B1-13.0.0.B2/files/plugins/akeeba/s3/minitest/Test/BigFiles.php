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
 * Upload, download and delete big files (over 1MB), without multipart uploads. Uses string or file sources.
 *
 * @package Akeeba\MiniTest\Test
 */
class BigFiles extends AbstractTest
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

	/**
	 * Should I use multipart (chunked) uploads?
	 *
	 * @var bool
	 */
	protected static $multipart = false;

	/**
	 * Chunk size for each multipart upload. Must be at least 5MB or the library overrides us.
	 *
	 * @var int
	 */
	protected static $uploadChunkSize = 5242880;

	/**
	 * Number of uploaded chunks.
	 *
	 * This is set by self::upload(). Zero for single part uploads, non-zero for multipart uploads.
	 *
	 * @var int
	 */
	protected static $numberOfChunks = 0;

	public static function upload5MBString(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::FIVE_MB, 'bigtest_5mb.dat');
	}

	public static function upload6MBString(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::SIX_MB, 'bigtest_6mb.dat');
	}

	public static function upload10MBString(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::TEN_MB, 'bigtest_10mb.dat');
	}

	public static function upload11MBString(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::ELEVEN_MB, 'bigtest_11mb.dat');
	}

	public static function upload5MBFile(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::FIVE_MB, 'bigtest_5mb.dat', false);
	}

	public static function upload6MBFile(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::SIX_MB, 'bigtest_6mb.dat', false);
	}

	public static function upload10MBFile(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::TEN_MB, 'bigtest_10mb.dat', false);
	}

	public static function upload11MBFile(Connector $s3, array $options): bool
	{
		return self::upload($s3, $options, self::ELEVEN_MB, 'bigtest_11mb.dat', false);
	}

	protected static function upload(Connector $s3, array $options, int $size, string $uri, bool $useString = true): bool
	{
		// Randomize the name. Required for archive buckets where you cannot overwrite data.
		$dotPos = strrpos($uri, '.');
		$uri    = substr($uri, 0, $dotPos) . '.' . md5(microtime(false)) . substr($uri, $dotPos);

		self::$numberOfChunks = 0;

		if ($useString)
		{
			$sourceData = self::getRandomData($size);
			$input      = Input::createFromData($sourceData);
		}
		else
		{
			// Create a file with random data
			$sourceFile = self::createFile($size);
			$input      = Input::createFromFile($sourceFile);
		}

		// Upload the file. Throws exception if it fails.
		$bucket = $options['bucket'];

		if (!self::$multipart)
		{
			$s3->putObject($input, $bucket, $uri);
		}
		else
		{
			// Get an upload session
			$uploadSession = $s3->startMultipart($input, $bucket, $uri);

			// This array holds the etags of uploaded parts. Used by finalizeMultipart.
			$eTags      = [];
			$partNumber = 1;

			while (true)
			{
				// We need to create a new input for each upload chunk
				if ($useString)
				{
					$input = Input::createFromData($sourceData);
				}
				else
				{
					$input = Input::createFromFile($sourceFile);
				}

				$input->setUploadID($uploadSession);
				$input->setEtags($eTags);
				$input->setPartNumber($partNumber);

				$etag = $s3->uploadMultipart($input, $bucket, $uri, [], self::$uploadChunkSize);

				// If the result was null we have no more file parts to process.
				if (is_null($etag))
				{
					break;
				}

				// Append the etag to the etags array
				$eTags[] = $etag;

				// Set the etags array in the Input object (required by finalizeMultipart)
				$input->setEtags($eTags);

				$partNumber++;
			}

			self::$numberOfChunks = count($eTags);

			// Finalize the multipart upload. Tells Amazon to construct the file from the uploaded parts.
			$s3->finalizeMultipart($input, $bucket, $uri);
		}

		// Tentatively accept that this method succeeded.
		$result = true;

		// Should I download the file and compare its contents?
		if (self::$downloadAfter)
		{
			if ($useString)
			{
				// Download the data. Throws exception if it fails.
				$downloadedData = $s3->getObject($bucket, $uri);

				// Compare the file contents.
				$result = self::areStringsEqual($sourceData, $downloadedData);
			}
			else
			{
				// Download the data. Throws exception if it fails.
				$downloadedFile = tempnam(self::getTempFolder(), 'as3');
				$s3->getObject($bucket, $uri, $downloadedFile);

				// Compare the file contents.
				$result = self::areFilesEqual($sourceFile, $downloadedFile);

				@unlink($downloadedFile);
			}
		}

		// Remove the local files
		if (!$useString)
		{
			@unlink($sourceFile);
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