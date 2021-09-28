<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * reCAPTCHA representation.
 * @see BxDolCaptcha
 */
class BxBaseCaptchaHCaptcha extends BxDolCaptcha
{
	protected $sApiUrl;
	protected $sVerifyUrl;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
		
		$this->_sSkin = 'light';
        $this->sApiUrl = 'https://js.hcaptcha.com/1/api.js';
        $this->sVerifyUrl = 'https://hcaptcha.com/siteverify';
    }

    /**
     * Display captcha.
     */
    public function display ($bDynamicMode = false)
    {
       $sCode = '';
        $aApiParams = array();
        if($bDynamicMode)  {
        	$sPostfix = $this->_sObject;
        	$sId = 'sys-captcha-' . $sPostfix;

        	$sOnLoadFunction = 'onLoadCallback' . $sPostfix;
        	$sOnLoadCode = "
	        	var " . $sOnLoadFunction . " = function() {
					hcaptcha.render('" . $sId . "', {
						'sitekey': '" . $this->_sKeyPublic . "',
						'theme': '" . $this->_sSkin . "'
					});
				};
		        ";

        	$aApiParams = array(
        		'onload' => $sOnLoadFunction,
        		'render' => 'explicit'
        	);

        	$sCode .= $this->_oTemplate->_wrapInTagJsCode($sOnLoadCode);
        	$sCode .= '<div id="' . $sId . '"></div>';
        }
        else {
        	$aApiParams = array(
        		'render' => 'onload'
        	);

        	$sCode .= '<div class="h-captcha" data-sitekey="' . $this->_sKeyPublic . '" data-theme="' . $this->_sSkin . '"></div>';
        }

        $aApiParams['hl'] = BxDolLanguages::getInstance()->getCurrentLanguage();
        $sCodeJs = $this->_oTemplate->addJs(bx_append_url_params($this->sApiUrl, $aApiParams), true);

        return $sCodeJs . $sCode;
    }

    /**
     * Check captcha.
     */
    public function check ()
    {
    	$mixedResponce = bx_file_get_contents($this->sVerifyUrl, array(
    		'secret' => $this->_sKeyPrivate, 
    		'response' => bx_process_input(bx_get('h-captcha-response'))
    	), 'post');
	
    	if($mixedResponce === false)
    		return false;

    	$aResponce = json_decode($mixedResponce, true); 	
    	if(isset($aResponce['success']) && $aResponce['success'] === true)
    		return true;

		if(!empty($aResponce['error-codes']))
			$this->_error = $aResponce['error-codes'];

		return false;
    }
}

/** @} */
