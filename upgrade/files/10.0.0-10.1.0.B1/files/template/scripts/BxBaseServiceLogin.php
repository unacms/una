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

    public function serviceTest ($n = 1)
    {
        return $n*2;
    }

    public function serviceMemberAuthCode($aAuthTypes = array())
    {
        if(empty($aAuthTypes) || !is_array($aAuthTypes))
            $aAuthTypes = BxDolDb::getInstance()->fromCache('sys_objects_auths', 'getAll', 'SELECT * FROM `sys_objects_auths`');

        $bCompact = getParam('site_login_social_compact') == 'on';

        $aTmplButtons = array();
        foreach($aAuthTypes as $iKey => $aItems) {
            $sTitle = _t($aItems['Title']);

            $aTmplButtons[] = array( 
            	'class' => ($bCompact ? 'sys-auth-compact bx-def-margin-sec-left-auto ' : '') . $aItems['Name'],
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

    public function serviceLoginFormOnly ($sParams = '', $sForceRelocate = '')
    {
    	if(strpos($sParams, 'no_join_text') === false)
    		$sParams = ($sParams != '' ? ' ' : '') . 'no_join_text';

    	return $this->serviceLoginForm($sParams, $sForceRelocate);
    }

    public function serviceLoginForm ($sParams = '', $sForceRelocate = '')
    {
        if (isLogged() && 'login' == bx_get('i')) {
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }

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
        
        $sFormCode = $oForm->getCode();

        BxDolTemplate::getInstance()->addJs(array('jquery.form.min.js'));
        
        $sJoinText = '';
        if (strpos($sParams, 'no_join_text') === false)
            $sJoinText = '<hr class="bx-def-hr bx-def-margin-sec-topbottom" /><div>' . _t('_sys_txt_login_description', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account')) . '</div>';

        $sAuth = '';
        if (strpos($sParams, 'no_auth_buttons') === false)
            $sAuth = $this->serviceMemberAuthCode();

        $sAjaxForm = '';
        if (strpos($sParams, 'ajax_form') !== false)
            $sAjaxForm = "<script>
                (bx_login_init_ajax_form = function () {
                    $('#sys-form-login').ajaxForm({
                        dataType: 'json',
                        success: function (oData) {
                            if ('undefined' !== typeof(oData['res']) && 'OK' == oData['res']) {
                                location.reload();
                            }
                            else if ('undefined' !== typeof(oData['form'])) {
                                $('#sys-form-login').replaceWith(oData['form']);
                                bx_login_init_ajax_form();
                            }
                        }
                    });
                })();
            </script>";
        
        return $sCustomHtmlBefore . $sAuth . $sFormCode . $sCustomHtmlAfter . $sJoinText . $sAjaxForm;

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
                if(!$oTwilio->sendSms($sPhoneNumber,  $sActivationText)){
                    return MsgBox(_t('_sys_txt_login_2fa_sms_error_occured'));
                }
            }
            $oSession = BxDolSession::getInstance();
            $oSession->setValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE, $sActivationCode);
            header('Location: ' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=login-step3'));
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
