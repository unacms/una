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
 * 4. REST Admin API -> Order
 * https://shopify.dev/docs/api/admin-rest/2023-10/resources/order
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
            'fields' => 'id,title,handle,body_html,tags,variants',
        ], 'get');
        
        if(empty($sProduct))
            return [];

        $aProduct = json_decode($sProduct, true);
        if(empty($aProduct) || !is_array($aProduct) || empty($aProduct['product']))
            return [];

        return $this->_prepareProduct($aProduct['product']);
    }
    
    public function getEntries($aParams)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sSample = $aParams['sample'];
        $aRequest = [
            'fields' => 'id,title,handle,body_html,tags,variants',
            'status' => isset($aParams['status']) ? $aParams['status'] : 'active'
        ];

        switch($sSample) {
            case 'ids':
                $aRequest = array_merge($aRequest, [
                    'ids' => implode(',', $aParams['ids']),
                ]);
                break;

            case 'title':
                $aRequest = array_merge($aRequest, [
                    'title' => $aParams['title'],
                ]);
                break;

            case 'all_id_title_pairs':
                $aRequest = array_merge($aRequest, [
                    'fields' => 'id,title',
                ]);
                break;

            case 'all':
                break;
        }

        $sProducts = $this->_call('products.json', $aRequest, 'get');
        if(empty($sProducts))
            return [];
        
        $aProducts = json_decode($sProducts, true);
        if(empty($aProducts) || !is_array($aProducts) || empty($aProducts['products']))
            return [];

        $aResults = [];
        foreach($aProducts['products'] as $aProduct) {
            switch($sSample) {
                case 'all_id_title_pairs':
                    $aResults[] = [
                        $CNF['FIELD_SOURCE'] => $aProduct['id'],
                        $CNF['FIELD_TITLE'] => $aProduct['title']
                    ];
                    break;
                 
                default:
                    $aResults[] = $this->_prepareProduct($aProduct);
            }
        }

        return $aResults;
    }
    
    protected function _prepareProduct($aProduct)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = [
            $CNF['FIELD_SOURCE'] => $aProduct['id'],
            $CNF['FIELD_TITLE'] => $aProduct['title'],
            $CNF['FIELD_URL'] => $this->_sStorefront . 'products/' . $aProduct['handle'],
            $CNF['FIELD_TEXT'] => $aProduct['body_html'],
            $CNF['FIELD_TAGS'] => $aProduct['tags'],
        ];

        if(!empty($aProduct['variants']) && is_array($aProduct['variants'])) {
            $aVariant = array_shift($aProduct['variants']);

            if(isset($aVariant['price']))
                $aResult[$CNF['FIELD_PRICE']] = (float)$aVariant['price'];

            if(isset($aVariant['inventory_quantity']))
                $aResult[$CNF['FIELD_QUANTITY']] = (int)$aVariant['inventory_quantity'];
        }
        
        return $aResult;
    }

    public function getOrders($aParams)
    {
        $sSample = $aParams['sample'];
        $aRequest = [
            'fields' => 'id,name,created_at,processed_at,current_total_price,current_total_tax,line_items',
            'financial_status' => isset($aParams['status']) ? $aParams['status'] : 'paid'
        ];

        switch($sSample) {
            case 'ids':
                $aRequest = array_merge($aRequest, [
                    'ids' => implode(',', $aParams['ids']),
                ]);
                break;

            case 'sales_by_product_id':
                $aRequest = array_merge($aRequest, [
                    'processed_at_min' => $this->_dateI2S($aParams['date_from']),
                ]);
                break;
        }

        $sOrders = $this->_call('orders.json', $aRequest, 'get');
        if(empty($sOrders))
            return [];
        
        $aOrders = json_decode($sOrders, true);
        if(empty($aOrders) || !is_array($aOrders) || empty($aOrders['orders']))
            return [];

        $aOrders = $aOrders['orders'];

        $aResults = [];
        foreach($aOrders as $aOrder) 
            foreach($aOrder['line_items'] as $aLineItem) {
                if($sSample == 'sales_by_product_id' && $aLineItem['product_id'] != $aParams['product_id'])
                    continue;

                $aResults[] = [
                    'id' => $aOrder['id'],
                    'created' => $aOrder['created_at'],
                    'processed' => $aOrder['processed_at'],
                    'name' => $aOrder['name'],
                    'product_id' => $aLineItem['product_id'],
                    'product_price' => (float)$aLineItem['price'],
                    'product_discounted' => (float)$aLineItem['pre_tax_price'],
                    'quantity' => (int)$aLineItem['quantity'],
                    'amount' => (int)$aLineItem['quantity'] * (float)$aLineItem['pre_tax_price']
                ];
            }

        return $aResults;
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
    
    protected function _dateI2S($iTimestamp)
    {
        return date("Y-m-d", $iTimestamp);
    }
}
