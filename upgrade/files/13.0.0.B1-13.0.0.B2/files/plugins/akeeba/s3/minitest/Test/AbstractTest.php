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
use RuntimeException;

abstract class AbstractTest
{
	const TEN_KB = 10240;

	const HUNDRED_KB = 102400;

	const SIX_HUNDRED_KB = 614400;

	const ONE_MB = 1048576;

	const FIVE_MB = 5242880;

	const SIX_MB = 6291456;

	const TEN_MB = 10485760;

	const ELEVEN_MB = 11534336;

	const BLOCK_SIZE = 1048576;

	const FILE_HASHING_ALGORITHM = 'sha256';

	public static function setup(Connector $s3, array $options): void
	{
		// Runs before any test
	}

	public static function teardown(Connector $s3, array $options): void
	{
		// Runs after all tests are finished
	}

	/**
	 * Creates a file with random data and returns its file path.
	 *
	 * The random data in the file repeats every $blockSize bytes when $reuseBlock is true.
	 *
	 * @param   int   $size  Size in files
	 *
	 * @param   int   $blockSize
	 * @param   bool  $reuseBlock
	 *
	 * @return  string  The full path to the temporary file.
	 */
	protected static function createFile(int $size = AbstractTest::SIX_HUNDRED_KB, int $blockSize = self::BLOCK_SIZE, bool $reuseBlock = true)
	{
		$tempFilePath = tempnam(self::getTempFolder(), 'as3');

		if ($tempFilePath === false)
		{
			throw new RuntimeException("Cannot create a temporary file.");
		}

		$fp = @fopen($tempFilePath, 'w', false);

		if ($fp === false)
		{
			throw new RuntimeException("Cannot write to the temporary file.");
		}

		$blockSize     = self::BLOCK_SIZE;
		$lastBlockSize = $size % $blockSize;
		$wholeBlocks   = (int) (($size - $lastBlockSize) / $blockSize);
		$blockData     = self::getRandomData();

		for ($i = 0; $i < $wholeBlocks; $i++)
		{
			fwrite($fp, $blockData);

			if (!$reuseBlock)
			{
				$blockData = self::getRandomData($blockSize);
			}
		}

		if ($lastBlockSize > 0)
		{
			fwrite($fp, $blockData, $lastBlockSize);
		}


		fclose($fp);

		return $tempFilePath;
	}

	/**
	 * Get a writeable temporary folder
	 *
	 * @return  string
	 */
	protected static function getTempFolder(): string
	{
		$tempPath = sys_get_temp_dir();

		if (!is_writable($tempPath))
		{
			$tempPath = __DIR__ . '/tmp';

			if (!is_dir($tempPath))
			{
				@mkdir($tempPath, 0755, true);
			}
		}

		if (!is_writable($tempPath))
		{
			throw new RuntimeException("Cannot get a writeable temporary path.");
		}

		return $tempPath;
	}

	/**
	 * Checks that two files are of equal length and contents
	 *
	 * @param   string  $referenceFilePath  The known, reference file
	 * @param   string  $unknownFilePath    The file we want to verify is the same as the reference file
	 *
	 * @return  bool
	 */
	protected static function areFilesEqual(string $referenceFilePath, string $unknownFilePath): bool
	{
		if (!file_exists($referenceFilePath) || !file_exists($unknownFilePath))
		{
			return false;
		}

		if (!is_file($referenceFilePath) || !is_file($unknownFilePath))
		{
			return false;
		}

		if (!is_readable($referenceFilePath) || !is_readable($unknownFilePath))
		{
			return false;
		}

		if (@filesize($referenceFilePath) !== @filesize($unknownFilePath))
		{
			return false;
		}

		return hash_file(self::FILE_HASHING_ALGORITHM, $referenceFilePath) === hash_file(self::FILE_HASHING_ALGORITHM, $unknownFilePath);
	}

	/**
	 * Checks that two strings are of equal length and contents
	 *
	 * @param   string  $referenceString  The known, reference file
	 * @param   string  $unknownString    The file we want to verify is the same as the reference file
	 *
	 * @return  bool
	 */
	protected static function areStringsEqual(string $referenceString, string $unknownString): bool
	{
		return $referenceString === $unknownString;
	}

	/**
	 * Returns random data of the specific size in bytes
	 *
	 * @param   int  $length  How many bytes of random data to return
	 *
	 * @return  string  Your random data
	 */
	protected static function getRandomData(int $length = self::BLOCK_SIZE): string
	{
		$blockData = '';

		if (substr(strtolower(PHP_OS), 0, 7) !== 'windows')
		{
			$fpRandom = @fopen('/dev/urandom', 'r');
			if ($fpRandom !== false)
			{
				$blockData = @fread($fpRandom, $length);
				@fclose($fpRandom);
			}
		}

		if (empty($blockData) && function_exists('random_bytes'))
		{
			try
			{
				$blockData = random_bytes($length);
			}
			catch (\Exception $e)
			{
				$blockData = '';
			}
		}

		if (empty($blockData) && function_exists('openssl_random_pseudo_bytes'))
		{
			$blockData = openssl_random_pseudo_bytes($length);
		}

		if (empty($blockData) && function_exists('mcrypt_create_iv'))
		{
			$blockData = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);

			if (empty($blockData))
			{
				$blockData = mcrypt_create_iv($length, MCRYPT_RAND);
			}
		}

		if (empty($blockData))
		{
			for ($i = 0; $i < $length; $i++)
			{
				$blockData .= ord(mt_rand(0, 255));
			}
		}

		return $blockData;
	}

	protected static function assert(bool $condition, string $message): void
	{
		if ($condition)
		{
			return;
		}

		throw new RuntimeException($message);
	}
}