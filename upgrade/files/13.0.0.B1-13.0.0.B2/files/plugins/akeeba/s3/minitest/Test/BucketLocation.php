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

class BucketLocation extends AbstractTest
{
	public static function getBucketLocation(Connector $s3, array $options): bool
	{
		$location = $s3->getBucketLocation($options['bucket']);

		self::assert($location === $options['region'], "Bucket ‘{$options['bucket']}′ reports being in region ‘{$location}′ instead of expected ‘{$options['region']}′");

		return true;
	}
}