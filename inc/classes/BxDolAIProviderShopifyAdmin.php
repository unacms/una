<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/*
 * Login for Owner/Collaborator account:
 * https://admin.shopify.com/store/your_shop/
 * 
 * Integration:
 * 0. Custom apps creation guide (for a shop Owner/Collaborator, not a Partner):
 * https://help.shopify.com/en/manual/apps/app-types/custom-apps
 * 
 * 1. Make authenticated requests to the REST Admin API:
 * https://shopify.dev/docs/apps/auth/admin-app-access-tokens
 * 
 * 2. REST Admin API reference:
 * https://shopify.dev/docs/api/admin-rest
 * 
 * 3. REST Admin API -> Product
 * https://shopify.dev/docs/api/admin-rest/2023-10/resources/product
 * 
 * 4. REST Admin API -> Order
 * https://shopify.dev/docs/api/admin-rest/2023-10/resources/order
 * 
 */
class BxDolAIProviderShopifyAdmin extends BxDolAIProvider
{
    public static $NAME = 'shopify_admin';

    protected $_sShopDomain;
    protected $_sAccessToken;

    protected $_sEndpoint;
    protected $_sStorefront;

    public function __construct($aProvider)
    {
        $this->_sName = self::$NAME;

        parent::__construct($aProvider);

        $this->_sShopDomain = $this->getOption('shop_domain');
        $this->_sAccessToken = $this->getOption('access_token');

        $this->_sEndpoint = "https://{$this->_sShopDomain}/admin/api/2023-10/";
        $this->_sStorefront = "https://{$this->_sShopDomain}/";
    }

    public function getEntry($sId)
    {
        $sProduct = $this->call('products/' . $sId . '.json', [
            'fields' => 'id,title,handle,body_html,tags,variants',
        ], 'get');
        
        if(empty($sProduct))
            return [];

        $aProduct = json_decode($sProduct, true);
        if(empty($aProduct) || !is_array($aProduct) || empty($aProduct['product']))
            return [];

        return $aProduct['product'];
    }

    public function processActionWebhook()
    {
        echo 'TODO: Webhook processing here';
    }

    public function call($sRequest, $aParams, $sMethod = 'post-json', $aHeaders = [])
    {
        $aHeaders[] = 'Content-Type: application/json';
        if(!empty($this->_sAccessToken))
            $aHeaders[] = 'X-Shopify-Access-Token: ' . $this->_sAccessToken;
        else
            $aHeaders[] = 'Authorization: Basic ' . base64_encode($this->_sApiKey . ':' . $this->_sApiSecretKey);

        $sResponse = bx_file_get_contents($this->_sEndpoint . $sRequest, $aParams, $sMethod, $aHeaders);
        if(empty($sResponse))
            return false;
        
        $aResponse = json_decode($sResponse, true);
        if(empty($aResponse) || !is_array($aResponse))
            return false;

        return $aResponse;
    }

    /**
     * Internal methods.
     */    
    protected function _dateI2S($iTimestamp)
    {
        return date("Y-m-d", $iTimestamp);
    }
}
