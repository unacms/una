<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4;

// Protection against direct access
defined('AKEEBAENGINE') || die();

/**
 * Shortcuts to often used access control privileges
 */
class Acl
{
	public const ACL_PRIVATE = 'private';

	public const ACL_PUBLIC_READ = 'public-read';

	public const ACL_PUBLIC_READ_WRITE = 'public-read-write';

	public const ACL_AUTHENTICATED_READ = 'authenticated-read';

	public const ACL_BUCKET_OWNER_READ = 'bucket-owner-read';

	public const ACL_BUCKET_OWNER_FULL_CONTROL = 'bucket-owner-full-control';
}
