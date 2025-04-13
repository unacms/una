<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2025 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\S3\Exception;

// Protection against direct access
defined('AKEEBAENGINE') || die();

use RuntimeException;
use Throwable;

class CannotOpenFileForRead extends RuntimeException
{
	public function __construct(string $file = "", int $code = 0, ?Throwable $previous = null)
	{
		$message = "Cannot open $file for reading";

		parent::__construct($message, $code, $previous);
	}

}
