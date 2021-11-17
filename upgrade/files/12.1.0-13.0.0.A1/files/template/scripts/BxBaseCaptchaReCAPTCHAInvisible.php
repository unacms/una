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
class BxBaseCaptchaReCAPTCHAInvisible extends BxDolCaptcha
{
	protected $sApiUrl;
	protected $sVerifyUrl;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
		
        $this->sApiUrl = 'https://www.google.com/recaptcha/api.js';
        $this->sVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    }

    /**
     * Display captcha.
     */
    public function display ($bDynamicMode = false)
    {
        $sCode = '';
        $sId = 'sys-captcha-' . $this->_sObject;

        $sCode .= '<div id="' . $sId . '"></div>';
        
        $sPostfix = bx_gen_method_name($this->_sObject);
        $sOnLoadFunction = 'onLoadCallback' . $sPostfix;
        $sOnSubmitFunction = 'onSubmitCallback' . $sPostfix;
        $sOnLoadCode = "
        	var " . $sOnLoadFunction . " = function() {
        		grecaptcha.render('" . $sId . "', {
        			'sitekey': '" . $this->_sKeyPublic . "',
        			'size': 'invisible',
        			'badge': 'inline'
        		});

        		grecaptcha.execute();
        	};
            ";
        $sCode .= $this->_oTemplate->_wrapInTagJsCode($sOnLoadCode);
        

        $sCode .= $this->_oTemplate->addJs(bx_append_url_params($this->sApiUrl, array(
        	'onload' => $sOnLoadFunction,
        	'render' => 'explicit',
			'hl' => BxDolLanguages::getInstance()->getCurrentLanguage()
        )), true);

        return $sCode;
    }

    /**
     * Check captcha.
     */
    public function check ()
    {
    	$mixedResponce = bx_file_get_contents($this->sVerifyUrl, array(
    		'secret' => $this->_sKeyPrivate, 
    		'response' => bx_process_input(bx_get('g-recaptcha-response')),
    		'remoteip' => getVisitorIP()
    	));
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
