<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System service for login form functionality.
 */
class BxBaseServiceLogin extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-login Login
     * @subsubsection bx_system_general-test test
     * 
     * @code bx_srv('system', 'test', [3], 'TemplServiceLogin'); @endcode
     * @code {{~system:test:TemplServiceLogin[3]~}} @endcode
     * 
     * Test method which returns provided number multiplied by 2
     * @param $n number 
     * 
     * @see BxBaseServiceLogin::serviceTest
     */
    /** 
     * @ref bx_system_general-create_account_form "create_account_form"
     */
    public function serviceTest ($n = 1)
    {
        return $n*2;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-login Login
     * @subsubsection bx_system_general-member_auth_code member_auth_code
     * 
     * @code bx_srv('system', 'member_auth_code', [], 'TemplServiceLogin'); @endcode
     * @code {{~system:member_auth_code:TemplServiceLogin~}} @endcode
     * 
     * Display external login buttons such as Facebook, Twitter, etc
     * @param $aAuthTypes optional list of external login buttons
     * 
     * @see BxBaseServiceLogin::serviceMemberAuthCode
     */
    /** 
     * @ref bx_system_general-member_auth_code "member_auth_code"
     */
    public function serviceMemberAuthCode($aAuthTypes = array())
    {
        if(empty($aAuthTypes) || !is_array($aAuthTypes))
            $aAuthTypes = BxDolDb::getInstance()->fromCache('sys_objects_auths', 'getAll', 'SELECT * FROM `sys_objects_auths`');

        $bCompact = getParam('site_login_social_compact') == 'on';

        $aTmplButtons = array();
        foreach($aAuthTypes as $iKey => $aItems) {
            $sTitle = _t($aItems['Title']);

            $aTmplButtons[] = array( 
            	'class' => ($bCompact ? 'sys-auth-compact ' : '') . $aItems['Name'],
                'href' => !empty($aItems['Link']) ? BX_DOL_URL_ROOT . $aItems['Link'] : 'javascript:void(0)',
                'title_alt' => bx_html_attribute($sTitle),
                'bx_if:show_onclick' => array(
                    'condition' => !empty($aItems['OnClick']),
                    'content' => array(
                        'onclick' => 'javascript:' . $aItems['OnClick']
                    )
                ),
                'bx_if:show_icon' => array(
                    'condition' => !empty($aItems['Icon']),
                    'content' => array(
                        'icon' => $aItems['Icon']
                    )
                ),
                'bx_if:show_title' => array(
                    'condition' => !$bCompact || empty($aItems['Icon']),
                    'content' => array(
                		'title' => $sTitle
                    )
                )
            );
            if ($aItems['Style'] != ""){
                $aStyles = unserialize($aItems['Style']);
                foreach($aStyles as $sKey => $aValues) {
                    BxDolTemplate::getInstance()->addCssStyle('.'. $aItems['Name'] .' ' . $sKey, $aValues);
                }
            }
        }

        if(empty($aTmplButtons) || !is_array($aTmplButtons))
            return '';

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCss(array('auth.css'));
        return $oTemplate->parseHtmlByName('auth.html', array(
            'bx_repeat:buttons' => $aTmplButtons,
            'class_container' => ($bCompact ? 'sys-auth-compact-container' : '')
        ));
    }

    /**
     * Display login form without join text
     * @see ::serviceLoginForm
     */
    public function serviceLoginFormOnly ($sParams = '', $sForceRelocate = '')
    {
    	if(strpos($sParams, 'no_join_text') === false)
    		$sParams = ($sParams != '' ? ' ' : '') . 'no_join_text';

    	return $this->serviceLoginForm($sParams, $sForceRelocate);
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-login Login
     * @subsubsection bx_system_general-login_form login_form
     * 
     * @code bx_srv('system', 'login_form', ["no_auth_buttons"], 'TemplServiceLogin'); @endcode
     * @code {{~system:login_form:TemplServiceLogin["no_auth_buttons"]~}} @endcode
     * 
     * Display login form
     * @param $sParams optional string with additional params:
     *          - no_join_text
     *          - no_auth_buttons
     *          - ajax_form
     * @param $sForceRelocate optional URL to redirect to after login
     * 
     * @see BxBaseServiceLogin::serviceLoginForm
     */
    /** 
     * @ref bx_system_general-login_form "login_form"
     */
    public function serviceLoginForm ($sParams = '', $sForceRelocate = '')
    {
        if (isLogged() && 'login' == bx_get('i')) {
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }

        $oPemalink = BxDolPermalinks::getInstance();
        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login');

        $sCustomHtmlBefore = '';
        $sCustomHtmlAfter = '';
        bx_alert('profile', 'show_login_form', 0, 0, array('oForm' => &$oForm, 'sParams' => &$sParams, 'sCustomHtmlBefore' => &$sCustomHtmlBefore, 'sCustomHtmlAfter' => &$sCustomHtmlAfter, 'aAuthTypes' => &$aAuthTypes));

        if (isset($oForm->aInputs['relocate'])) {
            if ($sForceRelocate && 0 === mb_stripos($sForceRelocate, BX_DOL_URL_ROOT))
                $oForm->aInputs['relocate']['value'] = $sForceRelocate;
            elseif ('homepage' == $sForceRelocate)
                $oForm->aInputs['relocate']['value'] = BX_DOL_URL_ROOT;
        }

        $aTmplVarsForm = [];
        if(!(bool)getParam('sys_account_disable_login_form'))
            $aTmplVarsForm['content'] = $oForm->getCode();

        $aTmplVarsAuth = [];
        if(strpos($sParams, 'no_auth_buttons') === false)
            $aTmplVarsAuth['content'] = $this->serviceMemberAuthCode();

        $aTmplVarsJoin = [];
        if(strpos($sParams, 'no_join_text') === false)
            $aTmplVarsJoin['url'] = bx_absolute_url($oPemalink->permalink('page.php?i=create-account'));

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('jquery.form.min.js'));        
        return $oTemplate->parseHtmlByName('block_login.html', [
            'custom_html_before' => $sCustomHtmlBefore,
            'bx_if:show_auth' => [
                'condition' => !empty($aTmplVarsAuth),
                'content' => $aTmplVarsAuth
            ],
            'bx_if:show_form' => [
                'condition' => !empty($aTmplVarsForm),
                'content' => $aTmplVarsForm,
            ],
            'custom_html_after' => $sCustomHtmlAfter,
            'bx_if:show_join' => [
                'condition' => !empty($aTmplVarsJoin),
                'content' => $aTmplVarsJoin
            ],
            'agreement' => _t('_sys_form_login_input_agreement_value', bx_absolute_url($oPemalink->permalink('page.php?i=terms')), bx_absolute_url($oPemalink->permalink('page.php?i=privacy'))),
            'bx_if:show_js_code' => [
                'condition' => strpos($sParams, 'ajax_form') !== false,
                'content' => []
            ]
        ]);

    }
    
    public function serviceLoginFormStep2 ()
    {
        if(isLogged()){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }

        $oSession = BxDolSession::getInstance();
        $iAccountId = $oSession->getValue(BX_ACCOUNT_SESSION_KEY_FOR_2FA_LOGIN_ACCOUNT_ID);
        if ($iAccountId == '')
            return false;

        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login_step2');
        $oForm->aFormAttrs['action'] = '';
        
        $oAccount = BxDolAccount::getInstance($iAccountId);
        $a = $oAccount->getInfo($iAccountId);
        $sPhoneNumber = trim($a['phone']);
            
        $oForm->initChecker(array('phone' => $sPhoneNumber));
        if ($oForm->isSubmittedAndValid()) {
            $sNewPhoneNumber = trim($oForm->getCleanValue('phone'));
            if ($sPhoneNumber != $sNewPhoneNumber)
                $oAccount->updatePhone($sNewPhoneNumber);
            $sActivationCode = rand(1000, 9999);
            $sActivationText =_t('_sys_txt_login_2fa_sms_text', $sActivationCode);
            $ret = null;
            bx_alert('account', 'before_2fa_send_sms', $oAccount->id(), false, array('phone_number' => $sPhoneNumber, 'sms_text' => $sActivationText, 'override_result' => &$ret));
            if ($ret === null) 
            {
                $oTwilio = BxDolTwilio::getInstance();
                if(!$oTwilio->sendSms($sNewPhoneNumber, $sActivationText)){
                    return MsgBox(_t('_sys_txt_login_2fa_sms_error_occured'));
                }
            }
            $oSession = BxDolSession::getInstance();
            $oSession->setValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE, $sActivationCode);
            header('Location: ' . bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=login-step3')));
        }
        return '<div class="bx-def-padding-sec-bottom">' . _t("_sys_txt_login_2fa_description") . '</div>' .$oForm->getCode();
    }
    
    public function serviceLoginFormStep3 ()
    {
        if(isLogged()){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }
        
        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login_step3');
        $oForm->aInputs['back']['caption'] = _t( $oForm->aInputs['back']['caption_src'], bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=login-step2')));
        $oForm->aFormAttrs['action'] = '';
        $oForm->initChecker();
        
        if ($oForm->isSubmittedAndValid()) {
            $oSession = BxDolSession::getInstance();
            $oSession->unsetValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE);
            $iAccountId = $oSession->getValue(BX_ACCOUNT_SESSION_KEY_FOR_2FA_LOGIN_ACCOUNT_ID);
            $oAccount = BxDolAccount::getInstance($iAccountId);
            $aAccount = bx_login($oAccount->id(), $oSession->getValue(BX_ACCOUNT_SESSION_KEY_FOR_2FA_LOGIN_IS_REMEMBER));
           
            $sUrlRelocate = $oForm->getCleanValue('relocate');
            if (!$sUrlRelocate || 0 !== strncmp($sUrlRelocate, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)))
                $sUrlRelocate = BX_DOL_ROLE_ADMIN == $oForm->getRole() ? BX_DOL_URL_STUDIO . 'launcher.php' : BX_DOL_URL_ROOT . 'member.php';

            bx_alert('account', 'login_after', $oAccount->id(),  false, array(
                'account' => $aAccount,
                'url_relocate' => &$sUrlRelocate               
            ));

            BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_TRANSITION);
            BxDolTemplate::getInstance()->setPageHeader (_t('_Please Wait'));
            BxDolTemplate::getInstance()->setPageContent ('page_main_code', MsgBox(_t('_Please Wait')));
            BxDolTemplate::getInstance()->setPageContent ('url_relocate', bx_html_attribute($sUrlRelocate, BX_ESCAPE_STR_QUOTE));
            
            header('Location: ' . $sUrlRelocate);
        } 
        return $oForm->getCode();
    }
}

/** @} */
