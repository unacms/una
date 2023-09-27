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

/**
 * Upload small files (under 1MB) using a file source
 *
 * @package Akeeba\MiniTest\Test
 */
class SmallFilesOnlyUpload extends SmallFiles
{
	public static function setup(Connector $s3, array $options): void
	{
		static::$deleteRemote  = false;
		static::$downloadAfter = false;

		parent::setup($s3, $options);
	}


}