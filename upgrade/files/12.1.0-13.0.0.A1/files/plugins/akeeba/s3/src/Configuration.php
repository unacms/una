<?php
/**
 * Akeeba Engine
 *
 * @package   akeebaengine
 * @copyright Copyright (c)2006-2020 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Engine\Postproc\Connector\S3v4;

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Holds the Amazon S3 confiugration credentials
 */
class Configuration
{
	/**
	 * Access Key
	 *
	 * @var  string
	 */
	protected $access = '';

	/**
	 * Secret Key
	 *
	 * @var  string
	 */
	protected $secret = '';

	/**
	 * Security token. This is only required with temporary credentials provisioned by an EC2 instance.
	 *
	 * @var  string
	 */
	protected $token = '';

	/**
	 * Signature calculation method ('v2' or 'v4')
	 *
	 * @var  string
	 */
	protected $signatureMethod = 'v2';

	/**
	 * AWS region, used for v4 signatures
	 *
	 * @var  string
	 */
	protected $region = 'us-east-1';

	/**
	 * Should I use SSL (HTTPS) to communicate to Amazon S3?
	 *
	 * @var  bool
	 */
	protected $useSSL = true;

	/**
	 * Should I use legacy, path-style access to the bucket? When it's turned off (default) we use virtual hosting style
	 * paths which are RECOMMENDED BY AMAZON per http://docs.aws.amazon.com/AmazonS3/latest/API/APIRest.html
	 *
	 * @var  bool
	 */
	protected $useLegacyPathStyle = false;

	/**
	 * Amazon S3 endpoint. You can use a custom endpoint with v2 signatures to access third party services which offer
	 * S3 compatibility, e.g. OwnCloud, Google Storage etc.
	 *
	 * @var  string
	 */
	protected $endpoint = 's3.amazonaws.com';

	/**
	 * Public constructor
	 *
	 * @param   string  $access            Amazon S3 Access Key
	 * @param   string  $secret            Amazon S3 Secret Key
	 * @param   string  $singatureMethod   Signature method (v2 or v4)
	 * @param   string  $region            Region, only required for v4 signatures
	 */
	function __construct($access, $secret, $singatureMethod = 'v2', $region = '')
	{
		$this->setAccess($access);
		$this->setSecret($secret);
		$this->setSignatureMethod($singatureMethod);
		$this->setRegion($region);
	}

	/**
	 * Get the Amazon access key
	 *
	 * @return  string
	 */
	public function getAccess()
	{
		return $this->access;
	}

	/**
	 * Set the Amazon access key
	 *
	 * @param   string  $access  The access key to set
	 *
	 * @throws  Exception\InvalidAccessKey
	 */
	public function setAccess($access)
	{
		if (empty($access))
		{
			throw new Exception\InvalidAccessKey;
		}

		$this->access = $access;
	}

	/**
	 * Get the Amazon secret key
	 *
	 * @return string
	 */
	public function getSecret()
	{
		return $this->secret;
	}

	/**
	 * Set the Amazon secret key
	 *
	 * @param   string  $secret  The secret key to set
	 *
	 * @throws  Exception\InvalidSecretKey
	 */
	public function setSecret($secret)
	{
		if (empty($secret))
		{
			throw new Exception\InvalidSecretKey;
		}

		$this->secret = $secret;
	}

	/**
	 * Return the security token. Only for temporary credentials provisioned through an EC2 instance.
	 *
	 * @return  string
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * Set the security token. Only for temporary credentials provisioned through an EC2 instance.
	 *
	 * @param  string  $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}

	/**
	 * Get the signature method to use
	 *
	 * @return  string
	 */
	public function getSignatureMethod()
	{
		return $this->signatureMethod;
	}

	/**
	 * Set the signature method to use
	 *
	 * @param   string  $signatureMethod  One of v2 or v4
	 *
	 * @throws  Exception\InvalidSignatureMethod
	 */
	public function setSignatureMethod($signatureMethod)
	{
		$signatureMethod = strtolower($signatureMethod);
		$signatureMethod = trim($signatureMethod);

		if (!in_array($signatureMethod, array('v2', 'v4')))
		{
			throw new Exception\InvalidSignatureMethod;
		}

		// If you switch to v2 signatures we unset the region.
		if ($signatureMethod == 'v2')
		{
			$this->setRegion('');

			/**
			 * If we are using Amazon S3 proper (not a custom endpoint) we have to set path style access to false.
			 * Amazon S3 does not support v2 signatures with path style access at all (it returns an error telling
			 * us to use the virtual hosting endpoint BUCKETNAME.s3.amazonaws.com).
			 */
			if (strpos($this->endpoint, 'amazonaws.com') !== false)
			{
				$this->setUseLegacyPathStyle(false);
			}

		}

		$this->signatureMethod = $signatureMethod;
	}

	/**
	 * Get the Amazon S3 region
	 *
	 * @return  string
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * Set the Amazon S3 region
	 *
	 * @param   string  $region
	 */
	public function setRegion($region)
	{
		/**
		 * You can only leave the region empty if you're using v2 signatures. Anything else gets you an exception.
		 */
		if (empty($region) && ($this->signatureMethod == 'v4'))
		{
			throw new Exception\InvalidRegion;
		}

		/**
		 * Setting a Chinese-looking region force-changes the endpoint but ONLY if you were using the original Amazon S3
		 * endpoint. If you're using a custom endpoint and provide a region with 'cn-' in its name we don't override
		 * your custom endpoint.
		 */
		if (($this->endpoint == 's3.amazonaws.com') && (substr($region, 0, 3) == 'cn-'))
		{
			$this->setEndpoint('amazonaws.com.cn');
		}

		$this->region = $region;
	}

	/**
	 * Is the connection to be made over HTTPS?
	 *
	 * @return  boolean
	 */
	public function isSSL()
	{
		return $this->useSSL;
	}

	/**
	 * Set the connection SSL preference
	 *
	 * @param  boolean  $useSSL  True to use HTTPS
	 */
	public function setSSL($useSSL)
	{
		$this->useSSL = $useSSL ? true : false;
	}

	/**
	 * Get the Amazon S3 endpoint
	 *
	 * @return  string
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * Set the Amazon S3 endpoint. Do NOT use a protocol
	 *
	 * @param   string  $endpoint  Custom endpoint, e.g. 's3.example.com' or 'www.example.com/s3api'
	 */
	public function setEndpoint($endpoint)
	{
		if (stristr($endpoint, '://'))
		{
			throw new Exception\InvalidEndpoint;
		}

		/**
		 * If you set a custom endpoint we have to switch to v2 signatures since our v4 implementation only supports
		 * Amazon endpoints.
		 */
		if ((strpos($endpoint, 'amazonaws.com') === false))
		{
			$this->setSignatureMethod('v2');
		}

		$this->endpoint = $endpoint;
	}

	/**
	 * Should I use legacy, path-style access to the bucket? You should only use it with custom endpoints. Amazon itself
	 * does not support path-style access since September 2020.
	 *
	 * @return  boolean
	 */
	public function getUseLegacyPathStyle()
	{
		return $this->useLegacyPathStyle;
	}

	/**
	 * Set the flag for using legacy, path-style access to the bucket
	 *
	 * @param  boolean  $useLegacyPathStyle
	 */
	public function setUseLegacyPathStyle($useLegacyPathStyle)
	{
		$this->useLegacyPathStyle = $useLegacyPathStyle;

		/**
		 * If we are using Amazon S3 proper (not a custom endpoint) we have to set path style access to false.
		 * Amazon S3 does not support v2 signatures with path style access at all (it returns an error telling
		 * us to use the virtual hosting endpoint BUCKETNAME.s3.amazonaws.com).
		 */
		if ((strpos($this->endpoint, 'amazonaws.com') !== false) && ($this->signatureMethod == 'v2'))
		{
			$this->useLegacyPathStyle = false;
		}
	}
}
