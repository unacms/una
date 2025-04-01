<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2025 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

/**
 * Automatic aliasing of the old namespace to the new, as you use each old class.
 */
spl_autoload_register(
	static function (string $className)
	{
		$oldNS = 'Akeeba\Engine\Postproc\Connector\S3v4';
		$newNS = 'Akeeba\S3';

		$className = trim($className, '\\');

		if (strpos($className, $oldNS) !== 0)
		{
			return false;
		}

		$newClassName = $newNS . '\\' . trim(substr($className, strlen($oldNS)), '\\');

		if (class_exists($newClassName, true))
		{
			class_alias($newClassName, $className, false);
		}

		return true;
	}
);
