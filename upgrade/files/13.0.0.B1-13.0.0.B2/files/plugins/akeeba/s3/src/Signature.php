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
 * Base class for request signing objects.
 */
abstract class Signature
{
	/**
	 * The request we will be signing
	 *
	 * @var  Request
	 */
	protected $request = null;

	/**
	 * Signature constructor.
	 *
	 * @param   Request  $request  The request we will be signing
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Get a signature object for the request
	 *
	 * @param   Request  $request  The request which needs signing
	 * @param   string   $method   The signature method, "v2" or "v4"
	 *
	 * @return  Signature
	 */
	public static function getSignatureObject(Request $request, string $method = 'v2'): self
	{
		$className = __NAMESPACE__ . '\\Signature\\' . ucfirst($method);

		return new $className($request);
	}

	/**
	 * Returns the authorization header for the request
	 *
	 * @return  string
	 */
	abstract public function getAuthorizationHeader(): string;

	/**
	 * Pre-process the request headers before we convert them to cURL-compatible format. Used by signature engines to
	 * add custom headers, e.g. x-amz-content-sha256
	 *
	 * @param   array  $headers     The associative array of headers to process
	 * @param   array  $amzHeaders  The associative array of amz-* headers to process
	 *
	 * @return  void
	 */
	abstract public function preProcessHeaders(array &$headers, array &$amzHeaders): void;

	/**
	 * Get a pre-signed URL for the request. Typically used to pre-sign GET requests to objects, i.e. give shareable
	 * pre-authorized URLs for downloading files from S3.
	 *
	 * @param   integer|null  $lifetime  Lifetime in seconds. NULL for default lifetime.
	 * @param   bool          $https     Use HTTPS ($hostBucket should be false for SSL verification)?
	 *
	 * @return  string  The authenticated URL, complete with signature
	 */
	abstract public function getAuthenticatedURL(?int $lifetime = null, bool $https = false): string;
}
