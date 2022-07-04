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

class BucketsList extends AbstractTest
{
	public static function listBucketsDetailed(Connector $s3, array $options): bool
	{
		$buckets = $s3->listBuckets(true);

		self::assert(is_array($buckets), "Detailed buckets list is not an array");
		self::assert(isset($buckets['owner']), "Detailed buckets list does not list an owner");
		self::assert(isset($buckets['owner']['id']), "Detailed buckets list does not list an owner's id");
		self::assert(isset($buckets['owner']['name']), "Detailed buckets list does not list an owner's name");
		self::assert(isset($buckets['buckets']), "Detailed buckets list does not list any buckets");

		foreach ($buckets['buckets'] as $bucketInfo)
		{
			self::assert(isset($bucketInfo['name']), "Bucket information does not list a name");
			self::assert(isset($bucketInfo['time']), "Bucket information does not list a created times");

			if ($bucketInfo['name'] === $options['bucket'])
			{
				return true;
			}
		}

		throw new RuntimeException("Detailed buckets list does not include configured bucket ‘{$options['bucket']}′");
	}

	public static function listBucketsSimple(Connector $s3, array $options): bool
	{
		$buckets = $s3->listBuckets(false);

		self::assert(is_array($buckets), "Simple buckets list is not an array");
		self::assert(in_array($options['bucket'], $buckets), "Simple buckets list does not include configured bucket ‘{$options['bucket']}′");

		return true;
	}

}