<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

use Akeeba\Engine\Postproc\Connector\S3v4\Configuration;
use Akeeba\Engine\Postproc\Connector\S3v4\Connector;
use Akeeba\Engine\Postproc\Connector\S3v4\Input;

// Necessary for including the library
define('AKEEBAENGINE', 1);

if (!file_exists(__DIR__ . '/../vendor/autoload.php'))
{
	die ('Please run composer install before running the mini test suite.');
}

// Use Composer's autoloader to load the library
/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require_once(__DIR__ . '/../vendor/autoload.php');

// Add the minitest PSR-4 path map to Composer's autoloader
$autoloader->addPsr4('Akeeba\\MiniTest\\', __DIR__);

function getAllTestClasses(): array
{
	static $testClasses = [];

	if (!empty($testClasses))
	{
		return $testClasses;
	}

	$folder = __DIR__ . '/Test';
	$di     = new DirectoryIterator($folder);

	foreach ($di as $entry)
	{
		if ($entry->isDot() || !$entry->isFile())
		{
			continue;
		}

		$baseName  = $entry->getBasename('.php');
		$className = '\\Akeeba\\MiniTest\\Test\\' . $baseName;

		if (!class_exists($className))
		{
			continue;
		}

		$reflectedClass = new ReflectionClass($className);

		if ($reflectedClass->isAbstract())
		{
			continue;
		}

		$testClasses[] = $className;
	}

	return $testClasses;
}

function getTestMethods(string $className): array
{
	static $classMethodMap = [];

	if (isset($classMethodMap[$className]))
	{
		return $classMethodMap[$className];
	}

	$classMethodMap[$className] = [];

	if (!class_exists($className))
	{
		return $classMethodMap[$className];
	}

	$reflectedClass = new ReflectionClass($className);
	$methods        = $reflectedClass->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_STATIC);

	$classMethodMap[$className] = array_map(function (ReflectionMethod $refMethod) {
		if ($refMethod->isPrivate() || $refMethod->isProtected() || $refMethod->isAbstract())
		{
			return null;
		}

		if (!$refMethod->isStatic())
		{
			return null;
		}

		return $refMethod->getName();
	}, $methods);

	$classMethodMap[$className] = array_filter($classMethodMap[$className], function ($method) {
		if (is_null($method))
		{
			return false;
		}

		if (in_array($method, ['setup', 'teardown']))
		{
			return false;
		}

		return true;
	});

	return $classMethodMap[$className];
}

function simplifyClassName(?string $className): string
{
	if (empty($className))
	{
		return '';
	}

	$namespace = __NAMESPACE__ . '\\Test\\';

	if (strpos($className, $namespace) === 0)
	{
		return substr($className, strlen($namespace));
	}

	return $className;
}

if (!file_exists(__DIR__ . '/config.php'))
{
	die ('Please rename config.dist.php to config.php and customise it before running the mini test suite.');
}

require __DIR__ . '/config.php';

global $testConfigurations;

$total      = 0;
$broken     = 0;
$failed     = 0;
$successful = 0;

foreach ($testConfigurations as $description => $setup)
{
	echo "▶ " . $description . PHP_EOL;
	echo str_repeat('〰', 80) . PHP_EOL . PHP_EOL;

	// Extract the configuration options
	if (!isset($setup['configuration']))
	{
		$setup['configuration'] = [];
	}

	$configOptions = array_merge([
		'access'      => DEFAULT_ACCESS_KEY,
		'secret'      => DEFAULT_SECRET_KEY,
		'region'      => DEFAULT_REGION,
		'bucket'      => DEFAULT_BUCKET,
		'signature'   => DEFAULT_SIGNATURE,
		'dualstack'   => DEFAULT_DUALSTACK,
		'path_access' => DEFAULT_PATH_ACCESS,
		'ssl'         => DEFAULT_SSL,
		'endpoint'    => null,
	], $setup['configuration']);

	// Extract the test classes/methods to run
	if (!isset($setup['tests']))
	{
		$setup['tests'] = getAllTestClasses();
	}

	$tests = $setup['tests'];

	if (!is_array($tests) || (is_array($tests) && in_array('*', $tests)))
	{
		$tests = getAllTestClasses();
	}

	// Create the S3 configuration object
	$s3Configuration = new Configuration($configOptions['access'], $configOptions['secret'], $configOptions['signature'], $configOptions['region']);
	$s3Configuration->setUseDualstackUrl($configOptions['dualstack']);
	$s3Configuration->setUseLegacyPathStyle($configOptions['path_access']);
	$s3Configuration->setSSL($configOptions['ssl']);

	if (!is_null($configOptions['endpoint']))
	{
		$s3Configuration->setEndpoint($configOptions['endpoint']);
	}

	// Create the connector object
	$s3Connector = new Connector($s3Configuration);

	// Run the tests
	foreach ($tests as $testInfo)
	{
		if (!is_array($testInfo))
		{
			$className = $testInfo;

			if (!class_exists($className))
			{
				$className = '\\Akeeba\\MiniTest\\Test\\' . $className;
			}

			if (!class_exists($className))
			{
				$total++;
				$broken++;
				echo "  ⁉️ Test class {$className} not found." . PHP_EOL;

				continue;
			}

			$testInfo = array_map(function ($method) use ($className) {
				return [$className, $method];
			}, getTestMethods($className));
		}
		else
		{
			[$className, $method] = $testInfo;

			if (!class_exists($className))
			{
				$className = '\\Akeeba\\MiniTest\\Test\\' . $className;
			}

			if (!class_exists($className))
			{
				$total++;
				$broken++;
				echo "  ⁉️ Test class {$className} not found." . PHP_EOL;

				continue;
			}

			$testInfo = [
				[$className, $method],
			];
		}

		$firstOne            = false;
		$className           = null;
		$callableSetup       = null;
		$callableTeardown    = null;
		$simplifiedClassname = simplifyClassName($className);

		if (!empty($testInfo))
		{
			$firstOne = array_shift($testInfo);
			array_unshift($testInfo, $firstOne);
		}

		if ($firstOne)
		{
			[$className,] = $firstOne;

			if ($className)
			{
				$callableSetup    = [$className, 'setup'];
				$callableTeardown = [$className, 'teardown'];
			}
		}

		if (is_callable($callableSetup))
		{
			[$classNameSetup, $method] = $callableSetup;
			$simplifiedClassname = simplifyClassName($classNameSetup);
			echo "  ⏱ Preparing {$simplifiedClassname}:{$method}…";
			call_user_func($callableSetup, $s3Connector, $configOptions);
			echo "\r     Prepared {$simplifiedClassname}   " . PHP_EOL;
		}

		foreach ($testInfo as $callable)
		{
			$total++;
			[$className, $method] = $callable;

			if (!class_exists($className))
			{
				$broken++;
				echo "  ⁉️ Test class {$className} not found." . PHP_EOL;

				continue;
			}

			if (!method_exists($className, $method))
			{
				$broken++;
				echo "  ⁉️ Method {$method} not found in test class {$className}." . PHP_EOL;

				continue;
			}

			echo "  ⏱ {$simplifiedClassname}:{$method}…";
			$errorException = null;

			try
			{
				$result = call_user_func([$className, $method], $s3Connector, $configOptions);
			}
			catch (Exception $e)
			{
				$result         = false;
				$errorException = $e;
			}

			echo "\r  " . ($result ? '✔' : '🚨') . " {$simplifiedClassname}:{$method}  " . PHP_EOL;

			if ($result)
			{
				$successful++;
				continue;
			}

			$failed++;

			if (is_null($errorException))
			{
				echo "    Returned false" . PHP_EOL;

				continue;
			}

			echo "    {$errorException->getCode()} – {$errorException->getMessage()}" . PHP_EOL;
			echo "    {$errorException->getFile()}({$errorException->getLine()})" . PHP_EOL . PHP_EOL;

			$errorLines = explode("\n", $errorException->getTraceAsString());

			foreach ($errorLines as $line)
			{
				echo "    $line" . PHP_EOL;
			}
		}

		if (is_callable($callableTeardown))
		{
			[$className, $method] = $callableSetup;
			echo "  ⏱ Tearing down {$className}:{$method}…";
			call_user_func($callableTeardown, $s3Connector, $configOptions);
			echo "\r     Teared down {$className}   " . PHP_EOL;
		}
	}

	echo PHP_EOL;
}

echo PHP_EOL;
echo str_repeat('⎺', 80) . PHP_EOL;
echo PHP_EOL;

echo "Summary:" . PHP_EOL;
if ($broken)
{
	echo "  Broken     : $broken" . PHP_EOL;
}
if ($failed)
{
	echo "  Failed     : $failed" . PHP_EOL;
}
if ($successful)
{
	echo "  Successful : $successful" . PHP_EOL;
}
echo "  Total      : $total" . PHP_EOL . PHP_EOL;

echo "Conclusion: " . PHP_EOL . "  ";

if ($failed > 0)
{
	echo "❌ FAILED 😭😭😭" . PHP_EOL;

	exit(1);
}

if ($successful === 0)
{
	echo "🔥 No tests executed! 🤪" . PHP_EOL;

	exit (3);
}

if ($broken > 0)
{
	echo "⁉️ SUCCESS but some tests are broken 🤦" . PHP_EOL;

	exit (2);
}

echo "✅ PASSED" . PHP_EOL;