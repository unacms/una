<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

namespace OAuth\OAuth1\Service;

use OAuth\OAuth1\Signature\SignatureInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\Common\Exception\Exception;

class Dolphin extends AbstractService
{
    public function __construct(CredentialsInterface $credentials, ClientInterface $httpClient, TokenStorageInterface $storage, SignatureInterface $signature, UriInterface $baseApiUri = null) {
        parent::__construct($credentials, $httpClient, $storage, $signature, $baseApiUri);

        if($baseApiUri === null)
            $this->baseApiUri = new Uri(BX_DOL_OAUTH_URL_BASE);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenEndpoint()
    {
        return new Uri(BX_DOL_OAUTH_URL_REQUEST_TOKEN);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        return new Uri(BX_DOL_OAUTH_URL_AUTHORIZE);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return new Uri(BX_DOL_OAUTH_URL_ACCESS_TOKEN);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseRequestTokenResponse($sResponseBody)
    {
    	$aData = array();
        parse_str($sResponseBody, $aData);

        if($aData === null || !is_array($aData))
            throw new TokenResponseException('Unable to parse response.');

        return $this->parseAccessTokenResponse($sResponseBody);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($sResponseBody)
    {
    	$aData = array();
        parse_str($sResponseBody, $aData);

        if($aData === null || !is_array($aData))
            throw new TokenResponseException('Unable to parse response.');
        else if(isset($aData['oauth_err_code']) && isset($aData['oauth_err_message']))
            throw new TokenResponseException('Error in retrieving token: "' . $aData['oauth_err_message'] . '"');

        $oToken = new StdOAuth1Token();

        $oToken->setRequestToken($aData['oauth_token']);
        $oToken->setRequestTokenSecret($aData['oauth_token_secret']);
        $oToken->setAccessToken($aData['oauth_token']);
        $oToken->setAccessTokenSecret($aData['oauth_token_secret']);

        $oToken->setEndOfLife(StdOAuth1Token::EOL_NEVER_EXPIRES);
        unset($aData['oauth_token'], $aData['oauth_token_secret']);
        $oToken->setExtraParams($aData);

        return $oToken;
    }
}

/** @} */
