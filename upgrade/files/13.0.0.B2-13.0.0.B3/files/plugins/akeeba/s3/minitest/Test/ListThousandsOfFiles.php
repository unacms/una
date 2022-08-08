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

class ListThousandsOfFiles extends AbstractTest
{
	private const PATH_PREFIX = 'massive/';

	public static function setup(Connector $s3, array $options): void
	{
		if (defined('CREATE_2100_FILES') && CREATE_2100_FILES === false)
		{
			return;
		}

		$data = self::getRandomData(128);

		echo "\nPopulating with 2100 files\n";

		for ($i = 1; $i <= 2100; $i++)
		{
			if ($i % 10 === 0)
			{
				echo "Uploading from $i...\n";
			}

			$uri   = sprintf('%stest_%04u.dat', self::PATH_PREFIX, $i);
			$input = Input::createFromData($data);
			$s3->putObject($input, $options['bucket'], $uri);
		}
	}

	public static function testGetAll(Connector $s3, array $options): bool
	{
		$listing = $s3->getBucket($options['bucket'], self::PATH_PREFIX);

		self::assert(is_array($listing), "The files listing must be an array");
		self::assert(count($listing) === 2100, "I am expecting to see 2100 files");

		for ($i = 1; $i <= 2100; $i++)
		{
			$key = sprintf('%stest_%04u.dat', self::PATH_PREFIX, $i);

			self::assert(array_key_exists($key, $listing), sprintf('Results should list object %s', $key));
		}

		return true;
	}

	public static function testGetHundred(Connector $s3, array $options): bool
	{
		$listing = $s3->getBucket($options['bucket'], self::PATH_PREFIX, null, 100);

		self::assert(is_array($listing), "The files listing must be an array");
		self::assert(count($listing) === 100, "I am expecting to see 100 files");

		for ($i = 1; $i <= 100; $i++)
		{
			$key = sprintf('%stest_%04u.dat', self::PATH_PREFIX, $i);

			self::assert(array_key_exists($key, $listing), sprintf('Results should list object %s', $key));
		}

		return true;
	}

	public static function testGetElevenHundred(Connector $s3, array $options): bool
	{
		$listing = $s3->getBucket($options['bucket'], self::PATH_PREFIX, null, 1100);

		self::assert(is_array($listing), "The files listing must be an array");
		self::assert(count($listing) === 1100, "I am expecting to see 1100 files");

		for ($i = 1; $i <= 1100; $i++)
		{
			$key = sprintf('%stest_%04u.dat', self::PATH_PREFIX, $i);

			self::assert(array_key_exists($key, $listing), sprintf('Results should list object %s', $key));
		}

		return true;
	}

	public static function testGetLastHundred(Connector $s3, array $options): bool
	{
		$listing = $s3->getBucket($options['bucket'], self::PATH_PREFIX . 'test_20', null);

		self::assert(is_array($listing), "The files listing must be an array");
		self::assert(count($listing) === 100, "I am expecting to see 100 files");

		for ($i = 2000; $i <= 2099; $i++)
		{
			$key = sprintf('%stest_%04u.dat', self::PATH_PREFIX, $i);

			self::assert(array_key_exists($key, $listing), sprintf('Results should list object %s', $key));
		}

		return true;
	}

}