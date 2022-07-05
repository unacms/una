<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4;

use Akeeba\Engine\Postproc\Connector\S3v4\Exception\PropertyNotFound;
use Akeeba\Engine\Postproc\Connector\S3v4\Response\Error;
use SimpleXMLElement;

// Protection against direct access
defined('AKEEBAENGINE') || die();

/**
 * Amazon S3 API response object
 *
 * @property   Error                        $error    Response error object
 * @property   string|SimpleXMLElement|null $body     Body data
 * @property   int                          $code     Response code
 * @property   array                        $headers  Any headers we may have
 */
class Response
{
	/**
	 * Error object
	 *
	 * @var  Error
	 */
	private $error = null;

	/**
	 * Response body
	 *
	 * @var  string|SimpleXMLElement|null
	 */
	private $body = null;

	/**
	 * Status code of the response, e.g. 200 for OK, 403 for Forbidden etc
	 *
	 * @var  int
	 */
	private $code = 0;

	/**
	 * Response headers
	 *
	 * @var  array
	 */
	private $headers = [];

	/**
	 * Response constructor.
	 */
	public function __construct()
	{
		$this->error = new Error();
	}

	/**
	 * Is this an error response?
	 *
	 * @return  bool
	 */
	public function isError(): bool
	{
		return is_null($this->error) || $this->error->isError();
	}

	/**
	 * Does this response have a body?
	 *
	 * @return  bool
	 */
	public function hasBody(): bool
	{
		return !empty($this->body);
	}

	/**
	 * Get the response error object
	 *
	 * @return  Error
	 */
	public function getError(): Error
	{
		return $this->error;
	}

	/**
	 * Set the response error object
	 *
	 * @param   Error  $error
	 */
	public function setError(Error $error): void
	{
		$this->error = $error;
	}

	/**
	 * Get the response body
	 *
	 * If there is no body set up you get NULL.
	 *
	 * If the body is binary data (e.g. downloading a file) or other non-XML data you get a string.
	 *
	 * If the body was an XML string – the standard Amazon S3 REST API response type – you get a SimpleXMLElement
	 * object.
	 *
	 * @return string|SimpleXMLElement|null
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Set the response body. If it's a string we'll try to parse it as XML.
	 *
	 * @param   string|SimpleXMLElement|null  $body
	 */
	public function setBody($body): void
	{
		$this->body = null;

		if (empty($body))
		{
			return;
		}

		$this->body = $body;

		$this->finaliseBody();
	}

	public function resetBody(): void
	{
		$this->body = null;
	}

	public function addToBody(string $data): void
	{
		if (empty($this->body))
		{
			$this->body = '';
		}

		$this->body .= $data;
	}

	public function finaliseBody(): void
	{
		if (!$this->hasBody())
		{
			return;
		}

		if (!isset($this->headers['type']))
		{
			$this->headers['type'] = 'text/plain';
		}

		if (is_string($this->body) &&
			(($this->headers['type'] == 'application/xml') || (substr($this->body, 0, 5) == '<?xml'))
		)
		{
			$this->body = simplexml_load_string($this->body);
		}

		if (is_object($this->body) && ($this->body instanceof SimpleXMLElement))
		{
			$this->parseBody();
		}
	}

	/**
	 * Returns the status code of the response
	 *
	 * @return  int
	 */
	public function getCode(): int
	{
		return $this->code;
	}

	/**
	 * Sets the status code of the response
	 *
	 * @param   int  $code
	 */
	public function setCode(int $code): void
	{
		$this->code = $code;
	}

	/**
	 * Get the response headers
	 *
	 * @return  array
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * Set the response headers
	 *
	 * @param   array  $headers
	 */
	public function setHeaders(array $headers): void
	{
		$this->headers = $headers;
	}

	/**
	 * Set a single header
	 *
	 * @param   string  $name   The header name
	 * @param   string  $value  The header value
	 *
	 * @return  void
	 */
	public function setHeader(string $name, string $value): void
	{
		$this->headers[$name] = $value;
	}

	/**
	 * Does a header by this name exist?
	 *
	 * @param   string  $name  The header to look for
	 *
	 * @return  bool  True if it exists
	 */
	public function hasHeader(string $name): bool
	{
		return array_key_exists($name, $this->headers);
	}

	/**
	 * Unset a response header
	 *
	 * @param   string  $name  The header to unset
	 *
	 * @return  void
	 */
	public function unsetHeader(string $name): void
	{
		if ($this->hasHeader($name))
		{
			unset ($this->headers[$name]);
		}
	}

	/**
	 * Magic getter for the protected properties
	 *
	 * @param   string  $name
	 *
	 * @return  mixed
	 */
	public function __get(string $name)
	{
		switch ($name)
		{
			case 'error':
				return $this->getError();
				break;

			case 'body':
				return $this->getBody();
				break;

			case 'code':
				return $this->getCode();
				break;

			case 'headers':
				return $this->getHeaders();
				break;
		}

		throw new PropertyNotFound("Property $name not found in " . get_class($this));
	}

	/**
	 * Magic setter for the protected properties
	 *
	 * @param   string  $name   The name of the property
	 * @param   mixed   $value  The value of the property
	 *
	 * @return  void
	 */
	public function __set(string $name, $value): void
	{
		switch ($name)
		{
			case 'error':
				$this->setError($value);
				break;

			case 'body':
				$this->setBody($value);
				break;

			case 'code':
				$this->setCode($value);
				break;

			case 'headers':
				$this->setHeaders($value);
				break;

			default:
				throw new PropertyNotFound("Property $name not found in " . get_class($this));
		}
	}

	/**
	 * Scans the SimpleXMLElement body for errors and propagates them to the Error object
	 */
	protected function parseBody(): void
	{
		if (!in_array($this->code, [200, 204]) &&
			isset($this->body->Code, $this->body->Message)
		)
		{
			$this->error = new Error(
				500,
				(string) $this->body->Code . ':' . (string) $this->body->Message
			);

			if (isset($this->body->Resource))
			{
				$this->error->setResource((string) $this->body->Resource);
			}
		}
	}
}
