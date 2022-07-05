<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4\Signature;

// Protection against direct access
defined('AKEEBAENGINE') || die();

use Akeeba\Engine\Postproc\Connector\S3v4\Signature;
use DateTime;

/**
 * Implements the Amazon AWS v4 signatures
 *
 * @see http://docs.aws.amazon.com/general/latest/gr/signature-version-4.html
 */
class V4 extends Signature
{
	/**
	 * Pre-process the request headers before we convert them to cURL-compatible format. Used by signature engines to
	 * add custom headers, e.g. x-amz-content-sha256
	 *
	 * @param   array  $headers     The associative array of headers to process
	 * @param   array  $amzHeaders  The associative array of amz-* headers to process
	 *
	 * @return  void
	 */
	public function preProcessHeaders(array &$headers, array &$amzHeaders): void
	{
		// Do we already have an SHA-256 payload hash?
		if (isset($amzHeaders['x-amz-content-sha256']))
		{
			return;
		}

		// Set the payload hash header
		$input = $this->request->getInput();

		if (is_object($input))
		{
			$requestPayloadHash = $input->getSha256();
		}
		else
		{
			$requestPayloadHash = hash('sha256', '', false);
		}

		$amzHeaders['x-amz-content-sha256'] = $requestPayloadHash;
	}

	/**
	 * Get a pre-signed URL for the request. Typically used to pre-sign GET requests to objects, i.e. give shareable
	 * pre-authorized URLs for downloading files from S3.
	 *
	 * @param   integer|null  $lifetime  Lifetime in seconds. NULL for default lifetime.
	 * @param   bool          $https     Use HTTPS ($hostBucket should be false for SSL verification)?
	 *
	 * @return  string  The presigned URL
	 */
	public function getAuthenticatedURL(?int $lifetime = null, bool $https = false): string
	{
		// Set the Expires header
		if (is_null($lifetime))
		{
			$lifetime = 10;
		}

		/**
		 * Authenticated URLs must always go through the generic regional endpoint, not the virtual hosting-style domain
		 * name. This means that if you have a bucket "example" in the EU West 1 (Ireland) region we have to go through
		 * http://s3-eu-west-1.amazonaws.com/example instead of http://example.amazonaws.com/ for all authenticated URLs
		 */
		$region   = $this->request->getConfiguration()->getRegion();
		$hostname = $this->getPresignedHostnameForRegion($region);
		$this->request->setHeader('Host', $hostname);

		// Set the expiration time in seconds
		$this->request->setHeader('Expires', (int) $lifetime);

		// Get the query parameters, including the calculated signature
		$bucket           = $this->request->getBucket();
		$uri              = $this->request->getResource();
		$headers          = $this->request->getHeaders();
		$protocol         = $https ? 'https' : 'http';
		$serialisedParams = $this->getAuthorizationHeader();

		// The query parameters are returned serialized; unserialize them, then build and return the URL.
		$queryParameters = unserialize($serialisedParams);

		$query = http_build_query($queryParameters);

		$url = $protocol . '://' . $headers['Host'] . $uri;
		$url .= (strpos($uri, '?') !== false) ? '&' : '?';
		$url .= $query;

		return $url;
	}

	/**
	 * Returns the authorization header for the request
	 *
	 * @return  string
	 */
	public function getAuthorizationHeader(): string
	{
		$verb           = strtoupper($this->request->getVerb());
		$resourcePath   = $this->request->getResource();
		$headers        = $this->request->getHeaders();
		$amzHeaders     = $this->request->getAmzHeaders();
		$parameters     = $this->request->getParameters();
		$bucket         = $this->request->getBucket();
		$isPresignedURL = false;

		// See the Connector class for the explanation behind this ugly workaround
		$amazonIsBraindead = isset($headers['workaround-braindead-error-from-amazon']);

		if ($amazonIsBraindead)
		{
			unset ($headers['workaround-braindead-error-from-amazon']);
		}

		// Get the credentials scope
		$signatureDate = new DateTime($headers['Date']);

		$credentialScope = $signatureDate->format('Ymd') . '/' .
			$this->request->getConfiguration()->getRegion() . '/' .
			's3/aws4_request';

		/**
		 * If the Expires header is set up we're pre-signing a download URL. The string to sign is a bit
		 * different in this case and we have to pass certain headers as query string parameters.
		 *
		 * @see http://docs.aws.amazon.com/general/latest/gr/sigv4-create-string-to-sign.html
		 */
		if (isset($headers['Expires']))
		{
			$gmtDate = clone $signatureDate;
			$gmtDate->setTimezone(new \DateTimeZone('GMT'));

			$parameters['X-Amz-Algorithm']  = "AWS4-HMAC-SHA256";
			$parameters['X-Amz-Credential'] = $this->request->getConfiguration()->getAccess() . '/' . $credentialScope;
			$parameters['X-Amz-Date']       = $gmtDate->format('Ymd\THis\Z');
			$parameters['X-Amz-Expires']    = sprintf('%u', $headers['Expires']);
			$token                          = $this->request->getConfiguration()->getToken();

			if (!empty($token))
			{
				$parameters['x-amz-security-token'] = $token;
			}

			unset($headers['Expires']);
			unset($headers['Date']);
			unset($headers['Content-MD5']);
			unset($headers['Content-Type']);

			$isPresignedURL = true;
		}

		// ========== Step 1: Create a canonical request ==========
		// See http://docs.aws.amazon.com/general/latest/gr/sigv4-create-canonical-request.html

		$canonicalHeaders   = "";
		$signedHeadersArray = [];

		// Calculate the canonical headers and the signed headers
		if ($isPresignedURL)
		{
			// Presigned URLs use UNSIGNED-PAYLOAD instead
			unset($amzHeaders['x-amz-content-sha256']);
		}

		$allHeaders = array_merge($headers, $amzHeaders);
		ksort($allHeaders);

		foreach ($allHeaders as $k => $v)
		{
			$lowercaseHeaderName = strtolower($k);

			if ($amazonIsBraindead && ($lowercaseHeaderName == 'content-length'))
			{
				/**
				 * I know it looks crazy. It is. Somehow Amazon requires me to do this and only on _some_ servers, mind
				 * you. This is something undocumented and which is not covered by their official SDK. I had to write
				 * my own library because of that and the official SDK's inability to upload large files without using
				 * at least as much memory as the file itself (which doesn't fly well for files around 2Gb, let me tell
				 * you that!).
				 */
				$v = "$v,$v";
			}

			$canonicalHeaders     .= $lowercaseHeaderName . ':' . trim($v) . "\n";
			$signedHeadersArray[] = $lowercaseHeaderName;
		}

		$signedHeaders = implode(';', $signedHeadersArray);

		if ($isPresignedURL)
		{
			$parameters['X-Amz-SignedHeaders'] = $signedHeaders;
		}

		// The canonical URI is the resource path
		$canonicalURI     = $resourcePath;
		$bucketResource   = '/' . $bucket;
		$regionalHostname = ($headers['Host'] != 's3.amazonaws.com') && ($headers['Host'] != $bucket . '.s3.amazonaws.com');

		// Special case: if the canonical URI ends in /?location the bucket name DOES count as part of the canonical URL
		// even though the Host is s3.amazonaws.com (in which case it normally shouldn't count). Yeah, I know, it makes
		// no sense!!!
		if (!$regionalHostname && ($headers['Host'] == 's3.amazonaws.com') && (substr($canonicalURI, -10) == '/?location'))
		{
			$regionalHostname = true;
		}

		if (!$regionalHostname && (strpos($canonicalURI, $bucketResource) === 0))
		{
			if ($canonicalURI === $bucketResource)
			{
				$canonicalURI = '/';
			}
			else
			{
				$canonicalURI = substr($canonicalURI, strlen($bucketResource));
			}
		}

		// If the resource path has a query yank it and parse it into the parameters array
		$questionMarkPos = strpos($canonicalURI, '?');

		if ($questionMarkPos !== false)
		{
			$canonicalURI = substr($canonicalURI, 0, $questionMarkPos);
			$queryString  = @substr($canonicalURI, $questionMarkPos + 1);
			@parse_str($queryString, $extraQuery);

			if (count($extraQuery))
			{
				$parameters = array_merge($parameters, $extraQuery);
			}
		}

		// The canonical query string is the string representation of $parameters, alpha sorted by key
		ksort($parameters);

		// We build the query the hard way because http_build_query in PHP 5.3 does NOT have the fourth parameter
		// (encoding type), defaulting to RFC 1738 encoding whereas S3 expects RFC 3986 encoding
		$canonicalQueryString = '';

		if (!empty($parameters))
		{
			$temp = [];

			foreach ($parameters as $k => $v)
			{
				$temp[] = $this->urlencode($k) . '=' . $this->urlencode($v);
			}

			$canonicalQueryString = implode('&', $temp);
		}

		// Get the payload hash
		$requestPayloadHash = 'UNSIGNED-PAYLOAD';

		if (isset($amzHeaders['x-amz-content-sha256']))
		{
			$requestPayloadHash = $amzHeaders['x-amz-content-sha256'];
		}

		// Calculate the canonical request
		$canonicalRequest = $verb . "\n" .
			$canonicalURI . "\n" .
			$canonicalQueryString . "\n" .
			$canonicalHeaders . "\n" .
			$signedHeaders . "\n" .
			$requestPayloadHash;

		$hashedCanonicalRequest = hash('sha256', $canonicalRequest);

		// ========== Step 2: Create a string to sign ==========
		// See http://docs.aws.amazon.com/general/latest/gr/sigv4-create-string-to-sign.html

		if (!isset($headers['Date']))
		{
			$headers['Date'] = '';
		}

		$stringToSign = "AWS4-HMAC-SHA256\n" .
			$headers['Date'] . "\n" .
			$credentialScope . "\n" .
			$hashedCanonicalRequest;

		if ($isPresignedURL)
		{
			$stringToSign = "AWS4-HMAC-SHA256\n" .
				$parameters['X-Amz-Date'] . "\n" .
				$credentialScope . "\n" .
				$hashedCanonicalRequest;
		}

		// ========== Step 3: Calculate the signature ==========
		// See http://docs.aws.amazon.com/general/latest/gr/sigv4-calculate-signature.html
		$kSigning = $this->getSigningKey($signatureDate);

		$signature = hash_hmac('sha256', $stringToSign, $kSigning, false);

		// ========== Step 4: Add the signing information to the Request ==========
		// See http://docs.aws.amazon.com/general/latest/gr/sigv4-add-signature-to-request.html

		$authorization = 'AWS4-HMAC-SHA256 Credential=' .
			$this->request->getConfiguration()->getAccess() . '/' . $credentialScope . ', ' .
			'SignedHeaders=' . $signedHeaders . ', ' .
			'Signature=' . $signature;

		// For presigned URLs we only return the Base64-encoded signature without the AWS format specifier and the
		// public access key.
		if ($isPresignedURL)
		{
			$parameters['X-Amz-Signature'] = $signature;

			return serialize($parameters);
		}

		return $authorization;
	}

	/**
	 * Calculate the AWS4 signing key
	 *
	 * @param   DateTime  $signatureDate  The date the signing key is good for
	 *
	 * @return  string
	 */
	private function getSigningKey(DateTime $signatureDate): string
	{
		$kSecret  = $this->request->getConfiguration()->getSecret();
		$kDate    = hash_hmac('sha256', $signatureDate->format('Ymd'), 'AWS4' . $kSecret, true);
		$kRegion  = hash_hmac('sha256', $this->request->getConfiguration()->getRegion(), $kDate, true);
		$kService = hash_hmac('sha256', 's3', $kRegion, true);
		$kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);

		return $kSigning;
	}

	private function urlencode(?string $toEncode): string
	{
		if (empty($toEncode))
		{
			return '';
		}

		return str_replace('+', '%20', urlencode($toEncode));
	}

	/**
	 * Get the correct hostname for the given AWS region
	 *
	 * @param   string  $region
	 *
	 * @return  string
	 */
	private function getPresignedHostnameForRegion(string $region): string
	{
		$endpoint         = 's3.' . $region . '.amazonaws.com';
		$dualstackEnabled = $this->request->getConfiguration()->getDualstackUrl();

		// If dual-stack URLs are enabled then prepend the endpoint
		if ($dualstackEnabled)
		{
			$endpoint = 's3.dualstack.' . $region . '.amazonaws.com';
		}

		if ($region == 'cn-north-1')
		{
			return $endpoint . '.cn';
		}

		return $endpoint;
	}
}
