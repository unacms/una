<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4\Response;

// Protection against direct access
defined('AKEEBAENGINE') || die();

/**
 * S3 response error object
 */
class Error
{
	/**
	 * Error code
	 *
	 * @var  int
	 */
	private $code = 0;

	/**
	 * Error message
	 *
	 * @var  string
	 */
	private $message = '';

	/**
	 * URI to the resource that throws the error
	 *
	 * @var  string
	 */
	private $resource = '';

	/**
	 * Create a new error object
	 *
	 * @param   int     $code      The error code
	 * @param   string  $message   The error message
	 * @param   string  $resource  The URI to the resource throwing the error
	 *
	 * @return  void
	 */
	function __construct($code = 0, $message = '', $resource = '')
	{
		$this->setCode($code);
		$this->setMessage($message);
		$this->setResource($resource);
	}

	/**
	 * Get the error code
	 *
	 * @return  int
	 */
	public function getCode(): int
	{
		return $this->code;
	}

	/**
	 * Set the error code
	 *
	 * @param   int  $code  Set to zeroo or a negative value to clear errors
	 *
	 * @return  void
	 */
	public function setCode(int $code): void
	{
		if ($code <= 0)
		{
			$code = 0;
			$this->setMessage('');
			$this->setResource('');
		}

		$this->code = $code;
	}

	/**
	 * Get the error message
	 *
	 * @return  string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * Set the error message
	 *
	 * @param   string  $message  The error message to set
	 *
	 * @return  void
	 */
	public function setMessage(string $message): void
	{
		$this->message = $message;
	}

	/**
	 * Get the URI of the resource throwing the error
	 *
	 * @return  string
	 */
	public function getResource(): string
	{
		return $this->resource;
	}

	/**
	 * Set the URI of the resource throwing the error
	 *
	 * @param   string  $resource
	 *
	 * @return  void
	 */
	public function setResource(string $resource): void
	{
		$this->resource = $resource;
	}

	/**
	 * Do we actually have an error?
	 *
	 * @return  bool
	 */
	public function isError(): bool
	{
		return ($this->code > 0) || !empty($this->message);
	}
}
