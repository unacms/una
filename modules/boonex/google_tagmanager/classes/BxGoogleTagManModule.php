<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GoogleTagMan Google Tag Manager
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGoogleTagManModule extends BxDolModule
{
    protected $_sGtmContainerId;
    
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);        
        
        $this->_sGtmContainerId = getParam('bx_googletagman_container_id');
    }

    public function serviceInjection($sName)
    {
        if (!$this->_sGtmContainerId)
            return '';

        switch ($sName) {
        case 'injection_head_begin':

            $oProfile = BxDolProfile::getInstance();
            $oAccount = $oProfile ? $oProfile->getAccountObject() : null;
            $aProfileInfo = $oProfile ? $oProfile->getInfo() : array('type' => 'guest', 'status' => 'active');
            $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($oProfile ? $oProfile->id() : 0);
            $aKeySecrets = $oProfile ? BxDolService::call('bx_oauth', 'get_clients_by', array(array('type' => 'user_id', 'user_id' => $oProfile->id()))) : array();

            $aDataLayer = array(
                'membership-id' => $aMembership['id'],
                'membership-name' => str_replace('_adm_prm_txt_level_', '', $aMembership['name']),
                'profile-type' => $aProfileInfo['type'],
                'profile-status' => $aProfileInfo['status'],                
                'account-email-confirmed' => (int)($oAccount ? $oAccount->isConfirmed() : 0),
                'account-profiles-count' => $oAccount ? $oAccount->getProfilesNumber() : 0,
                'keys-secrets-count' => count($aKeySecrets),
            );
            $sDataLayer = json_encode($aDataLayer);

            return <<<EOF
<script>
  dataLayer = [$sDataLayer];
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$this->_sGtmContainerId}');</script>
<!-- End Google Tag Manager -->
EOF;
        case 'injection_header':
            
            return <<<EOF
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$this->_sGtmContainerId}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
EOF;
        }
    }

    public function serviceTrackingCodeRegister()
    {
        if (!$this->_sGtmContainerId)
            return '';
        
        if (!bx_get('register'))
            return '';

        return $this->getTrackingCode (array(
            'event' => 'register',
        ));
    }
    
    public function serviceTrackingCodeDownloadMarketProduct()
    {
        if (!$this->_sGtmContainerId)
            return '';

        if (!($iProductId = (int)bx_get('id')))
            return '';

        if (!($aProductMarket = BxDolService::call('bx_market', 'get_entry_by', array('id', $iProductId))))
            return '';

        $aParams = array(
            'event' => 'market-download',
            'product-id' => $aProductMarket['id'],
            'product-name' => $aProductMarket['name'],
            'product-title' => $aProductMarket['title'],
            'product-added' => $aProductMarket['added'],
            'product-changed' => $aProductMarket['changed'],
            'product-thumb' => $aProductMarket['thumb'],
            'product-price-single' => $aProductMarket['price_single'],
            'product-price-recurring' => $aProductMarket['price_recurring'],
            'product-duration-recurring' => $aProductMarket['duration_recurring'],
            'product-favorites' => $aProductMarket['favorites'],
            'product-featured' => $aProductMarket['featured'],
            'product-comments' => $aProductMarket['comments'],
            'vendor-display-name' => BxDolProfile::getInstance($aProductMarket['author'])->getDisplayName(),
            'vendor-profile-id' => $aProductMarket['author'],
        );

        $sParams = json_encode($aParams);
        return <<<EOF
<script>
    $(document).ready(function () {
        $('.bx-market-attachment a').on('click', function () {
            dataLayer.push($.extend($sParams, {'filename': $(this).attr('title')}));
        });
    });
</script>
EOF;
    }

    public function serviceTrackingCodePurchase($aTransaction, $aProducts)
    {
        if (!$this->_sGtmContainerId)
            return '';
        
        $a = array(
            'event' => 'purchase',
            'amount' => number_format((float)$aTransaction['amount'], 2, '.', ''),
            'ecommerce' => array(
                'currencyCode' => BxDolPayments::getInstance()->getOption('default_currency_code'),
                'purchase' => array (
                    'actionField' => array (
                        'id' => $aTransaction['order'],
                        'revenue'=> number_format((float)$aTransaction['amount'], 2, '.', ''),
                    ),
                    'products' => array()
                ),
            ),
        );

        $iAuthorIdIndex = 0;
        $iModuleIdIndex = 1;
        $iProductIdIndex = 2;
        $iQuantityIndex = 3;
            
        foreach ($aProducts as $sProduct) {
            $aProduct = explode('_', $sProduct);

            if (!($aModule = BxDolModuleQuery::getInstance()->getModuleById($aProduct[$iModuleIdIndex])))
                continue;
            
            $oVendor = BxDolProfile::getInstance($aProduct[$iAuthorIdIndex]);
            if (!$oVendor)
                $oVendor = BxDolProfileUndefined::getInstance();
            $sVendorDisplayName = $oVendor->getDisplayName();

            $aProductFormatted = array(
                'id' => $aProduct[$iProductIdIndex],
                'brand' => $sVendorDisplayName,
                'vendor-display-name' => $sVendorDisplayName,
                'vendor-profile-id' => $aProduct[$iAuthorIdIndex],
                'module-id' => $aModule['id'],
                'module-name' => $aModule['name'],
                'quantity' => $aProduct[$iQuantityIndex],
            );

            if ('bx_market' == $aModule['name'] && ($aProductMarket = BxDolService::call('bx_market', 'get_entry_by', array('id', $aProduct[$iProductIdIndex])))) {
                $aProductFormatted = array_merge($aProductFormatted, array(
                    'name' => $aProductMarket['name'],
                    'price' => number_format((float)($aProductMarket['price_single'] ? $aProductMarket['price_single'] : $aProductMarket['price_recurring']), 2, '.', ''),

                    'product-id' => $aProductMarket['id'],
                    'product-name' => $aProductMarket['name'],
                    'product-title' => $aProductMarket['title'],
                    'product-added' => $aProductMarket['added'],
                    'product-changed' => $aProductMarket['changed'],
                    'product-thumb' => $aProductMarket['thumb'],
                    'product-price-single' => $aProductMarket['price_single'],
                    'product-price-recurring' => $aProductMarket['price_recurring'],
                    'product-duration-recurring' => $aProductMarket['duration_recurring'],
                    'product-favorites' => $aProductMarket['favorites'],
                    'product-featured' => $aProductMarket['featured'],
                    'product-comments' => $aProductMarket['comments'],
                ));
            }
            
            $a['ecommerce']['purchase']['products'][] = $aProductFormatted;

            if (!isset($a['product-id'])) {
                unset($aProductFormatted['id']);
                $a = array_merge($a, $aProductFormatted);
            }
        }

        return $this->getTrackingCode ($a);
    }

    protected function getTrackingCode ($aParams, $bWrapInScriptTag = true)
    {
        $sParams = json_encode($aParams);
        $s = "dataLayer.push($sParams);";
        return $bWrapInScriptTag ? "\n<script>\n" . $s . "\n</script>\n" : $s;
    }
}

/** @} */
