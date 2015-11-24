<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * reCAPTCHA representation.
 * @see BxDolCaptcha
 */
class BxBaseCaptchaReCAPTCHA extends BxDolCaptcha
{
    protected $_bJsCssAdded = false;
    protected $_oTemplate;

    protected $_sSkin = 'custom';
    protected $_error = null;
    protected $_sKeyPublic;
    protected $_sKeyPrivate;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_sKeyPublic = getParam('sys_recaptcha_key_public');
        $this->_sKeyPrivate = getParam('sys_recaptcha_key_private');
    }

    /**
     * Display captcha.
     */
    public function display ($bDynamicMode = false)
    {
        // TODO: captcha don't display error in javascript mode, try to find the way on how to pass error code in this mode

        $sId = 'sys-captcha-' . time() . rand(0, PHP_INT_MAX);
        $sJsObject = 'Recaptcha';

        $sInit = $sJsObject . ".create('" . $this->_sKeyPublic . "', '" . $sId . "', {
			lang: '" . BxDolLanguages::getInstance()->getCurrentLanguage() . "',
			theme: '" . $this->_sSkin . "',
			custom_theme_widget: '" . $sId . "',
			callback: " . $sJsObject . ".focus_response_field
		});";

        return $this->_addJsCss($bDynamicMode) . $this->_oTemplate->parseHtmlByName('reCaptcha.html', array(
        	'js_object' => $sJsObject, 
        	'id' => $sId,
        	'bx_if:show_common' => array(
        		'condition' => !$bDynamicMode,
        		'content' => array(
        			'js_init' => $sInit,
        		)
        	),
        	'bx_if:show_dynamic' => array(
        		'condition' => $bDynamicMode,
        		'content' => array(
        			'js_object' => $sJsObject,
        			'js_init' => $sInit,
        		)
        	),
        ));
    }

    /**
     * Check captcha.
     */
    public function check ()
    {
        require_once(BX_DIRECTORY_PATH_PLUGINS . 'recaptcha/recaptchalib.php');

        $oResp = recaptcha_check_answer(
            $this->_sKeyPrivate,
            $_SERVER["REMOTE_ADDR"],
            bx_process_input(bx_get('recaptcha_challenge_field')),
            bx_process_input(bx_get('recaptcha_response_field'))
        );

        if (!$oResp->is_valid) {
            $this->_error = $oResp->error;
            return false;
        }

        return true;
    }

    /**
     * Check if captcha is available, like all API keys are specified.
     */
    public function isAvailable ()
    {
        return !empty($this->_sKeyPublic) && !empty($this->_sKeyPrivate);
    }

    /**
     * Add css/js files which are needed for display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false)
    {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';
        $this->_oTemplate->addJs('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');
        $this->_bJsCssAdded = true;
        return '';
    }
}

/** @} */
