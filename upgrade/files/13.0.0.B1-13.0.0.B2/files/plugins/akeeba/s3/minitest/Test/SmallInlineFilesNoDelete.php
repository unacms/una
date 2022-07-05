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

/**
 * Upload and download small files (under 1MB) using a string source
 *
 * @package Akeeba\MiniTest\Test
 */
class SmallInlineFilesNoDelete extends SmallInlineFiles
{
	public static function setup(Connector $s3, array $options): void
	{
		self:: $deleteRemote = false;

		parent::setup($s3, $options);
	}
}