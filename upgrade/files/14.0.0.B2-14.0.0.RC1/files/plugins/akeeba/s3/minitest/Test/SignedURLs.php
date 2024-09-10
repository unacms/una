<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2024 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\MiniTest\Test;


use Akeeba\S3\Acl;
use Akeeba\S3\Connector;
use Akeeba\S3\Input;
use RuntimeException;

class SignedURLs extends AbstractTest
{
	public static function signedURLPublicObject(Connector $s3, array $options): bool
	{
		return static::signedURL($s3, $options, Acl::ACL_PUBLIC_READ);
	}

	public static function signedURLPublicObjectSpaces(Connector $s3, array $options): bool
	{
		return static::signedURL($s3, array_merge($options, [
			'spaces' => true
		]), Acl::ACL_PUBLIC_READ);
	}

	public static function signedURLPrivateObject(Connector $s3, array $options): bool
	{
		return static::signedURL($s3, $options, Acl::ACL_PRIVATE);
	}

	public static function signedURLPrivateObjectSpaces(Connector $s3, array $options): bool
	{
		return static::signedURL($s3, array_merge($options, [
			'spaces' => true
		]), Acl::ACL_PRIVATE);
	}

	private static function signedURL(Connector $s3, array $options, string $aclPrivilege): bool
	{
		$spaces   = isset($options['spaces']) && boolval($options['spaces']);
		$tempData = static::getRandomData(AbstractTest::TEN_KB);
		$input    = Input::createFromData($tempData);
		$prefix   = $spaces ? 'test file' : 'test';
		$uri      = $prefix . '.' . hash('md5', microtime(false)) . '.dat';

		$s3->putObject($input, $options['bucket'], $uri, $aclPrivilege);

		$downloadURL    = $s3->getAuthenticatedURL($options['bucket'], $uri, null, $options['ssl']);

		echo "\n\tDownload URL: $downloadURL\n";

		$downloadedData = @file_get_contents($downloadURL);

		try
		{
			$s3->deleteObject($options['bucket'], $uri);
		}
		catch (\Exception $e)
		{
			// Ignore deletion errors
		}

		if ($downloadedData === false)
		{
			throw new RuntimeException("Failed to download from signed URL ‘{$downloadURL}′");
		}

		static::assert(static::areStringsEqual($tempData, $downloadedData), "Wrong data received from signed URL ‘{$downloadURL}′");

		return true;
	}
}