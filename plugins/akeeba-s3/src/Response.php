<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4;

use Akeeba\Engine\Postproc\Connector\S3v4\Exception\PropertyNotFound;
use Akeeba\Engine\Postproc\Connector\S3v4\Response\Error;

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Amazon S3 API response object
 *
 * @property   Error  $error    Response error object
 * @property   mixed  $body     Body data
 * @property   int    $code     Response code
 * @property   array  $headers  Any headers we may have
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
	 * @var  \SimpleXMLElement|string|null
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
	private $headers = array();

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
	public function isError()
	{
		return is_null($this->error) || $this->error->isError();
	}

	/**
	 * Does this response have a body?
	 *
	 * @return  bool
	 */
	public function hasBody()
	{
		return !empty($this->body);
	}

	/**
	 * Get the eresponse error object
	 *
	 * @return  Error
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Set the response error object
	 *
	 * @param   Error  $error
	 */
	public function setError(Error $error)
	{
		$this->error = $error;
	}

	/**
	 * Get the response body
	 *
	 * @return null|string|\SimpleXMLElement
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Set the response body. If it's a string we'll try to parse it as XML.
	 *
	 * @param   null|string|\SimpleXMLElement  $body
	 */
	public function setBody($body)
	{
		$this->body = null;

		if (empty($body))
		{
			return;
		}

		$this->body = $body;

		$this->finaliseBody();
	}

	public function resetBody()
	{
		$this->body = null;
	}

	public function addToBody($data)
	{
		if (empty($this->body))
		{
			$this->body = '';
		}

		$this->body .= $data;
	}

	public function finaliseBody()
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

		if (is_object($this->body) && ($this->body instanceof \SimpleXMLElement))
		{
			$this->parseBody();
		}
	}

	/**
	 * Returns the status code of the response
	 *
	 * @return  int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Sets the status code of the response
	 *
	 * @param   int  $code
	 */
	public function setCode($code)
	{
		$this->code = (int) $code;
	}

	/**
	 * Get the response headers
	 *
	 * @return  array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Set the response headers
	 *
	 * @param   array  $headers
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
	}

	/**
	 * Set a single header
	 *
	 * @param   string  $name   The header name
	 * @param   mixed   $value  The header value
	 *
	 * @return  void
	 */
	public function setHeader($name, $value)
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
	public function hasHeader($name)
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
	public function unsetHeader($name)
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
	public function __get($name)
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
	public function __set($name, $value)
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
	protected function parseBody()
	{
		if (!in_array($this->code, array(200, 204)) &&
			isset($this->body->Code, $this->body->Message)
		)
		{
			$this->error = new Error(
				(string)$this->body->Code,
				(string)$this->body->Message
			);

			if (isset($this->body->Resource))
			{
				$this->error->setResource((string)$this->body->Resource);
			}
		}
	}
}
