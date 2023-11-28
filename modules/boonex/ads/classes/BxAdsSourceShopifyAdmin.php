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
 */
class BxAdsSourceShopifyAdmin extends BxAdsSource
{
    public static $NAME = 'shopify_admin';

    protected $_sShopDomain;
    protected $_sAccessToken;

    protected $_sEndpoint;
    protected $_sStorefront;

    public function __construct($iProfile, $aSource, &$oModule)
    {
        $this->_sName = self::$NAME;

        parent::__construct($iProfile, $aSource, $oModule);

        $this->_sShopDomain = $this->getOption('shop_domain');
        $this->_sAccessToken = $this->getOption('access_token');

        $this->_sEndpoint = "https://{$this->_sShopDomain}/admin/api/2023-10/";
        $this->_sStorefront = "https://{$this->_sShopDomain}/";
    }

    public function getEntry($sId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sProduct = $this->_call('products/' . $sId . '.json', [
            'fields' => 'id,title,handle,body_html,variants',
        ], 'get');
        
        if(empty($sProduct))
            return [];

        $aProduct = json_decode($sProduct, true);
        if(empty($aProduct) || !is_array($aProduct) || empty($aProduct['product']))
            return [];

        $aProduct = $aProduct['product'];

        $aResult = [
            $CNF['FIELD_TITLE'] => ['type' => 'text', 'value' => $aProduct['title']],
            $CNF['FIELD_URL'] => ['type' => 'hidden', 'value' => $this->_sStorefront . 'products/' . $aProduct['handle']],
            $CNF['FIELD_TEXT'] => ['type' => 'text_html', 'value' => $aProduct['body_html']],
        ];

        if(!empty($aProduct['variants']) && is_array($aProduct['variants'])) {
            $aVariant = array_shift($aProduct['variants']);

            if(isset($aVariant['price']))
                $aResult[$CNF['FIELD_PRICE']] = ['type' => 'text', 'value' => (float)$aVariant['price']];

            if(isset($aVariant['inventory_quantity']))
                $aResult[$CNF['FIELD_QUANTITY']] = ['type' => 'text', 'value' => (int)$aVariant['inventory_quantity']];
        }

        return $aResult;
    }

    /**
     * Internal methods.
     */
    protected function _call($sRequest, $aParams, $sMethod = 'post-json', $aHeaders = [])
    {
        $aHeaders[] = 'Content-Type: application/json';
        if(!empty($this->_sAccessToken))
            $aHeaders[] = 'X-Shopify-Access-Token: ' . $this->_sAccessToken;
        else
            $aHeaders[] = 'Authorization: Basic ' . base64_encode($this->_sApiKey . ':' . $this->_sApiSecretKey);

        return bx_file_get_contents($this->_sEndpoint . $sRequest, $aParams, $sMethod, $aHeaders);
    }
}
