<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectTemplate extends BxBaseModConnectTemplate
{
    function __construct($oConfig, $oDb)
    {
    	$this->MODULE = 'bx_stripe_connect';

        parent::__construct($oConfig, $oDb);
    }

    public function displayBlockConnect()
    {
    	$CNF = $this->_oConfig->CNF;

    	$sApiId = $this->_oConfig->getApiId();
    	if(empty($sApiId))
    		return '';

		$iVendor = bx_get_logged_profile_id();
		$aVendorAccount = $this->_oDb->getAccount(array('type' => 'author', 'author' => $iVendor));
		if(!empty($aVendorAccount) && is_array($aVendorAccount))
			return $this->displayBlockAccount($aVendorAccount);

		$aVendor = BxDolModule::getInstance($this->MODULE)->getProfileInfo($iVendor);
    	$bVendorStripe = BxDolPayments::getInstance()->isPaymentProvider($iVendor, $CNF['STRIPE']);

    	$aRequestParams = array(
			'response_type' => 'code',
			'client_id' => $sApiId,
			'scope' => $this->_oDb->getParam($CNF['PARAM_API_SCOPE']),
			'redirect_uri' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . $CNF['URI_REDIRECT'],
			'stripe_landing' => $bVendorStripe ? 'login' : 'register'
		);

		if(!$bVendorStripe)
			$aRequestParams = array_merge($aRequestParams, array(
				'stripe_user[email]' => $aVendor['email'],
				'stripe_user[url]' => urlencode($aVendor['link']),
				'stripe_user[first_name]' => $aVendor['name']
			));

    	$this->addCss(array('main.css'));
    	return $this->parseHtmlByName('block_connect.html', array(
    		'link' => bx_append_url_params($CNF['URL_API_AUTHORIZE'], $aRequestParams, false)
    	));
    }
    
    public function displayBlockAccount($aAccount)
    {
    	$CNF = $this->_oConfig->CNF;

    	$sApiId = $this->_oConfig->getApiId();
    	if(empty($sApiId))
    		return '';

		$this->addCss(array('main.css'));
		$this->addJs(array('main.js'));
    	$this->addJsTranslation(array('_bx_stripe_connect_wrn_disconnect'));
    	return $this->parseHtmlByName('block_account.html', array(
    		'js_object' => $this->_oConfig->getJsObject('main'),
    		'id' => $aAccount['id'],
    		'user_id' => $aAccount['user_id'],
    	    'public_key' => $aAccount['public_key'],
    		'access_token' => $aAccount['access_token'],
	    	'added' => bx_time_js($aAccount['added']),
    	    'keys_usage' => _t('_bx_stripe_connect_txt_keys_usage', BxDolPayments::getInstance()->getDetailsUrl()),
    		'js_code' => $this->getJsCode('main')
    	));
    }

	public function displayProfileLink($mixedProfile)
    {
    	if(!is_array($mixedProfile))
    		$mixedProfile = BxDolModule::getInstance($this->MODULE)->getProfileInfo((int)$mixedProfile);

    	return $this->parseHtmlByName('link.html', array(
            'href' => $mixedProfile['link'],
            'title' => bx_html_attribute(!empty($mixedProfile['title']) ? $mixedProfile['title'] : $mixedProfile['name']),
            'content' => $mixedProfile['name']
        ));
    }
}

/** @} */
