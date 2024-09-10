<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2024 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\MiniTest\Test;


use Akeeba\S3\Connector;
use Akeeba\S3\Input;

/**
 * Upload, download and delete small XML files (under 1MB) using a string source
 *
 * @package Akeeba\MiniTest\Test
 */
class SmallInlineXMLFiles extends SmallFiles
{
	public static function upload10KbRoot(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::TEN_KB, 'root_10kb.xml');
	}

	public static function upload10KbRootGreek(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::TEN_KB, 'δοκιμή_10kb.xml');
	}

	public static function upload10KbFolderGreek(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::TEN_KB, 'ο_φάκελός_μου/δοκιμή_10kb.xml');
	}

	public static function upload600KbRoot(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::SIX_HUNDRED_KB, 'root_600kb.xml');
	}

	public static function upload10KbFolder(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::TEN_KB, 'my_folder/10kb.xml');
	}

	public static function upload600KbFolder(Connector $s3, array $options): bool
	{
		return static::upload($s3, $options, AbstractTest::SIX_HUNDRED_KB, 'my_folder/600kb.xml');
	}

	protected static function upload(Connector $s3, array $options, int $size, string $uri): bool
	{
		// Randomize the name. Required for archive buckets where you cannot overwrite data.
		$dotPos = strrpos($uri, '.');
		$uri    = substr($uri, 0, $dotPos) . '.' . hash('md5', microtime(false)) . substr($uri, $dotPos);

		// Create some random data to upload
		$sourceData = static::createXMLFile($size);

		// Upload the data. Throws exception if it fails.
		$bucket = $options['bucket'];
		$input  = Input::createFromData($sourceData);

		$s3->putObject($input, $bucket, $uri);

		// Tentatively accept that this method succeeded.
		$result = true;

		// Should I download the file and compare its contents with my random data?
		if (static::$downloadAfter)
		{
			$downloadedData = $s3->getObject($bucket, $uri);

			$result = static::areStringsEqual($sourceData, $downloadedData);
		}

		// Should I delete the remotely stored file?
		if (static::$deleteRemote)
		{
			// Delete the remote file. Throws exception if it fails.
			$s3->deleteObject($bucket, $uri);
		}

		return $result;
	}

	private static function createXMLFile(int $size): string
	{
		$out = <<< XML
<?xml version="1.0" encoding="utf-8" ?>
<root>
XML;

		$chunks = floor(($size - 55) / 1024);

		for ($i = 1; $i <= $chunks; $i++)
		{
			$randomBlock = static::genRandomData(1024 - 63);
			$out .= <<< XML
		<element>
			<id>$i</id>
			<data><![CDATA[$randomBlock]]></data>
		</element>
XML;

		}


		$out .= <<< XML
</root>
XML;

		return $out;
	}

	private static function genRandomData(int $length): string
	{
		$chars     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890';
		$maxLength = strlen($chars) - 1;
		$salt      = '';

		for ($i = 0; $i < $length; $i++)
		{
			$salt .= substr($chars, random_int(0, $maxLength), 1);
		}

		return $salt;
	}
}