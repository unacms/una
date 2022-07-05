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
use RuntimeException;

class SignedURLs extends AbstractTest
{
	public static function signedURLPublicObject(Connector $s3, array $options): bool
	{
		return self::signedURL($s3, $options, Acl::ACL_PUBLIC_READ);
	}

	public static function signedURLPrivateObject(Connector $s3, array $options): bool
	{
		return self::signedURL($s3, $options, Acl::ACL_PRIVATE);
	}

	private static function signedURL(Connector $s3, array $options, string $aclPrivilege): bool
	{
		$tempData = self::getRandomData(AbstractTest::TEN_KB);
		$input    = Input::createFromData($tempData);
		$uri      = 'test.' . md5(microtime(false)) . '.dat';

		$s3->putObject($input, $options['bucket'], $uri, $aclPrivilege);

		$downloadURL    = $s3->getAuthenticatedURL($options['bucket'], $uri, null, $options['ssl']);
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

		self::assert(self::areStringsEqual($tempData, $downloadedData), "Wrong data received from signed URL ‘{$downloadURL}′");

		return true;
	}
}