<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\MiniTest\Test;


use Akeeba\S3\Connector;
use RuntimeException;

class BucketsList extends AbstractTest
{
	public static function listBucketsDetailed(Connector $s3, array $options): bool
	{
		$buckets = $s3->listBuckets(true);

		static::assert(is_array($buckets), "Detailed buckets list is not an array");
		static::assert(isset($buckets['owner']), "Detailed buckets list does not list an owner");
		static::assert(isset($buckets['owner']['id']), "Detailed buckets list does not list an owner's id");
		static::assert(isset($buckets['owner']['name']), "Detailed buckets list does not list an owner's name");
		static::assert(isset($buckets['buckets']), "Detailed buckets list does not list any buckets");

		foreach ($buckets['buckets'] as $bucketInfo)
		{
			static::assert(isset($bucketInfo['name']), "Bucket information does not list a name");
			static::assert(isset($bucketInfo['time']), "Bucket information does not list a created times");

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

		static::assert(is_array($buckets), "Simple buckets list is not an array");
		static::assert(in_array($options['bucket'], $buckets), "Simple buckets list does not include configured bucket ‘{$options['bucket']}′");

		return true;
	}

}