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
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);        
    }

    public function serviceInjection($sName)
    {
        if (!($sGtmContainerId = getParam('bx_googletagman_container_id')))
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
})(window,document,'script','dataLayer','$sGtmContainerId');</script>
<!-- End Google Tag Manager -->
EOF;
        case 'injection_header':
            
            return <<<EOF
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=$sGtmContainerId"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
EOF;
        }
    }

    public function serviceTrackingCodeRegister()
    {
        if (!bx_get('register'))
            return '';

        return $this->getTrackingCode (array(
            'event' => 'register',
        ));
    }
    
    public function serviceTrackingCodePurchase($aTransaction, $aProducts)
    {
        $a = array(
            'event' => 'purchase',
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

        foreach ($aProducts as $sProduct) {
            $aProduct = explode('_', $sProduct);
            $a['ecommerce']['purchase']['products'][] = array(
                'id' => $aProduct[2],
                'brand' => BxDolProfile::getInstance($aProduct[0])->getDisplayName(),

                'vendor-profile-id' => $aProduct[0],
                'module-id' => $aProduct[1],                
                'quantity' => $aProduct[3],
            );
        }
        
        return $this->getTrackingCode ($a);
    }

    protected function getTrackingCode ($aParams)
    {
        $sParams = json_encode($aParams);
        return <<<EOF
<script type="text/javascript">
    dataLayer.push($sParams);
</script>
EOF;
    }
}

/** @} */
