<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4;

/**
 * Amazon S3 Storage Classes
 *
 * When you want to override the default storage class of the bucket pass
 * array('X-Amz-Storage-Class' => StorageClass::STANDARD)
 * in the $headers array of Connector::putObject().
 *
 * Alternatively, run the $headers array through setStorageClass(), e.g.
 * $headers = array(); // You can put your stuff here
 * StorageClass::setStorageClass($headers, StorageClass::STANDARD);
 * $connector->putObject($myInput, 'bucketname', 'path/to/object.dat', Acl::PRIVATE, $headers)
 *
 * @see https://aws.amazon.com/s3/storage-classes/
 */
class StorageClass
{
	/**
	 * Amazon S3 Standard (S3 Standard)
	 */
	public const STANDARD = 'STANDARD';

	/**
	 * Reduced redundancy storage
	 *
	 * Not recommended anymore. Use INTELLIGENT_TIERING instead.
	 */
	public const REDUCED_REDUNDANCY = 'REDUCED_REDUNDANCY';

	/**
	 * Amazon S3 Intelligent-Tiering (S3 Intelligent-Tiering)
	 */
	public const INTELLIGENT_TIERING = 'INTELLIGENT_TIERING';

	/**
	 * Amazon S3 Standard-Infrequent Access (S3 Standard-IA)
	 */
	public const STANDARD_IA = 'STANDARD_IA';

	/**
	 * Amazon S3 One Zone-Infrequent Access (S3 One Zone-IA)
	 */
	public const ONEZONE_IA = 'ONEZONE_IA';

	/**
	 * Amazon S3 Glacier (S3 Glacier)
	 */
	public const GLACIER = 'GLACIER';

	/**
	 * Amazon S3 Glacier Deep Archive (S3 Glacier Deep Archive)
	 */
	public const DEEP_ARCHIVE = 'DEEP_ARCHIVE';

	/**
	 * Manipulate the $headers array, setting the X-Amz-Storage-Class header for the requested storage class.
	 *
	 * This method will automatically remove any previously set X-Amz-Storage-Class header, case-insensitive. The reason
	 * for that is that Amazon headers **are** case-insensitive and you could easily end up having two separate headers
	 * with competing storage classes. This would mess up the signature and your request would promptly fail.
	 *
	 * @param   array   $headers
	 * @param   string  $storageClass
	 *
	 * @return  void
	 */
	public static function setStorageClass(array &$headers, string $storageClass): void
	{
		// Remove all previously set X-Amz-Storage-Class headers (case-insensitive)
		$killList = [];

		foreach ($headers as $key => $value)
		{
			if (strtolower($key) === 'x-amz-storage-class')
			{
				$killList[] = $key;
			}
		}

		foreach ($killList as $key)
		{
			unset($headers[$key]);
		}

		// Add the new X-Amz-Storage-Class header
		$headers['X-Amz-Storage-Class'] = $storageClass;
	}
}