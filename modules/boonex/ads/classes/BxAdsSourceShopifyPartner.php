<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxAdsSource.php');

/*
 * Note. A custom app created via Partner Dashboard should use the OAuth authentication flow.
 * Methods makeAuthorization and authorizeApplication are needed for this purpose.
 * 
 * Login for Partner account:
 * https://accounts.shopify.com/
 * 
 * 
 * Integration:
 * 0. Create custom app in Partner dashboard.
 * 
 * 1. Authorization flow:
 * https://shopify.dev/docs/apps/auth/oauth
 * 
 */

class BxAdsSourceShopifyPartner extends BxAdsSource
{
    public static $NAME = 'shopify_partner';

    protected $_sShopDomain;
    protected $_sApiKey;
    protected $_sApiSecretKey;
    protected $_aScopes;

    protected $_sEndpoint;

    protected $_sToken;
    protected $_aLastResponseHeaders;   

    protected $_sCacheKey;
    protected $_iCacheLifetime;
    protected $_oCache;

    public function __construct($iProfile, $aSource, $oModule)
    {
        $this->_sName = self::$NAME;

        parent::__construct($iProfile, $aSource, &$oModule);

        $this->_sShopDomain = $this->getOption('shop_domain');
        $this->_sApiKey = $this->getOption('api_key');
        $this->_sApiSecretKey = $this->getOption('api_secret_key');
        $this->_sToken = $this->getOption('token');
        $this->_aScopes = [];

        $this->_sEndpoint = "https://{$this->_sShopDomain}";

        $this->_sCacheKey = $this->_oModule->_oConfig->getCacheKeyShopify();
        $this->_iCacheLifetime = $this->_oModule->_oConfig->getCacheLifetimeShopify();

        $sCacheEngine = $this->_oModule->_oConfig->getCacheEngineShopify();
        $this->_oCache = bx_instance('BxDolCache' . $sCacheEngine);
        if(!$this->_oCache->isAvailable())
            $this->_oCache = bx_instance('BxDolCacheFile');
    }

    public function makeAuthorization($aScopes = [])
    {
        $sNonce = bin2hex(random_bytes(10));

        if(empty($aScopes))
            $aScopes = $this->_aScopes;

        $aCached = $this->_oCache->getData($this->_sCacheKey, $this->_iCacheLifetime);

        if(empty($aCached))
            $aCached = [];
        $aCached[$this->_sShopDomain] = ['nonce' => $sNonce, 'scopes' => $aScopes];

        $this->_oCache->setData($this->_sCacheKey, $aCached, $this->_iCacheLifetime);

        return $this->_getAuthorizeUrl($aScopes, BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'shopify_handle_callback', $sNonce);
    }
    
    public function authorizeApplication($aRequestData)
    {
        $aRequiredKeys = ['code', 'hmac', 'state', 'shop'];
        foreach($aRequiredKeys as $sRk)
            if(!in_array($sRk, array_keys($aRequestData)))
                return $this->_log("The provided request data is missing one of the following keys: " . implode(', ', $aRequiredKeys));

        $aCached = $this->_oCache->getData($this->_sCacheKey, $this->_iCacheLifetime);
        if(!isset($aCached[$this->_sShopDomain]))
            return $this->_log("Nonce cannot be found: " . $this->_sShopDomain);

        $sNonce = $aCached[$this->_sShopDomain]['nonce'];
        if($aRequestData['state'] !== $sNonce)
            return $this->_log("The provided nonce ($sNonce) did not match the nonce provided by Shopify ({$aRequestData['state']})");

        if(!filter_var($aRequestData['shop'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME))
            return $this->_log("The shop provided by Shopify ({$aRequestData['shop']}) is an invalid hostname.");

        if($aRequestData['shop'] !== $this->_sShopDomain)
            return $this->_log("The shop provided by Shopify ({$aRequestData['shop']}) does not match the shop provided to this API ({$this->_sShopDomain})");

        // Check HMAC signature. See https://help.shopify.com/api/getting-started/authentication/oauth#verification
        $aHmacSource = [];
        foreach($aRequestData as $sKey => $sValue) {
            if($sKey === 'hmac')
                continue;

            // Replace the characters as specified by Shopify in the keys and values
            $aPatterns = ['&' => '%26', '%' => '%25'];
            $aPatternsKey = array_merge($aPatterns, ['=' => '%3D']);
            $sKey = str_replace(array_keys($aPatternsKey), array_values($aPatternsKey), $sKey);
            $sValue = str_replace(array_keys($aPatterns), array_values($aPatterns), $sValue);

            $aHmacSource[] = $sKey . '=' . $sValue;
        }

        // Sort the key value pairs lexographically and then generate the HMAC signature of the provided data
        sort($aHmacSource);
        $sHmacBase = implode('&', $aHmacSource);
        $sHmacString = hash_hmac('sha256', $sHmacBase, $this->_sApiSecretKey);

        // Verify that the signatures match
        if($sHmacString !== $aRequestData['hmac'])
            return $this->_log("The HMAC provided by Shopify ({$requestData['hmac']}) doesn't match the HMAC verification ($sHmacString).");

        // Make the access token request to Shopify
        $aResponse = $this->_callAuthorize(['code' => $requestData['code']]);

        /**
         * TODO: Decode data and retrieve Access token.
         * 
        // Decode the response from Shopify
        $data = json_decode($response->getBody());

        // Set the access token
        $this->setToken($data->access_token);
         */

        return $aResponse;
    }

    /**
     * Internal methods.
     */
    protected function _getAuthorizeUrl($aScopes, $sRedirectUrl, $sNonce, $bOnlineAccessMode = false)
    {
        $aParams = [
            'client_id' => $this->_sApiKey,
            'scope' => implode(',', $aScopes),
            'redirect_uri' => $sRedirectUrl,
            'state' => $sNonce,
        ];

        if ($bOnlineAccessMode)
            $args['grant_options[]'] = 'per-user';

        return bx_append_url_params("https://" . $this->_sShopDomain . "/admin/oauth/authorize", $aParams);
    }

    protected function _callAuthorize($aParams)
    {
        $aParams = array_merge([
            'client_id'     => $this->_sApiKey,
            'client_secret' => $this->_sApiSecretKey
        ], $aParams);

        return $this->_call('admin/oauth/access_token', $aParams, 'post-json', ['Content-Type' => 'application/json']);
    }
    
    protected function _call($sRequest, $aParams, $sMethod = 'post-json', $aHeaders = [])
    {
        $aHeaders[] = 'Content-Type: application/json';
        if(!empty($this->_sToken))
            $aHeaders[] = 'X-Shopify-Access-Token: ' . $this->_sToken;
        else
            $aHeaders[] = 'Authorization: Basic ' . base64_encode($this->_sApiKey . ':' . $this->_sApiSecretKey);

        return bx_file_get_contents($this->_sEndpoint . $sRequest, $aParams, $sMethod, $aHeaders);
    }

    protected function _log($sMessage, $bUseLog = false)
    {
        if($bUseLog) {
            //TODO: Use bx_log here.
        }
        else
            throw new Exception($sMessage);

        return false;
    }    
}
