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
            	'class' => $bCompact ? 'sys-auth-compact bx-def-margin-sec-left-auto' : '',
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
        }

        BxDolTemplate::getInstance()->addCss(array('auth.css'));

        return BxDolTemplate::getInstance()->parseHtmlByName('auth.html', array(
            'bx_repeat:buttons' => $aTmplButtons
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
        if(isLogged())
            return false;

        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login');

        $sCustomHtmlBefore = '';
        $sCustomHtmlAfter = '';
        bx_alert('profile', 'show_login_form', 0, 0, array('oForm' => $oForm, 'sParams' => &$sParams, 'sCustomHtmlBefore' => &$sCustomHtmlBefore, 'sCustomHtmlAfter' => &$sCustomHtmlAfter, 'aAuthTypes' => &$aAuthTypes));

        if ($sForceRelocate && 0 === mb_stripos($sForceRelocate, BX_DOL_URL_ROOT))
            $oForm->aInputs['relocate']['value'] = $sForceRelocate;
        elseif ('homepage' == $sForceRelocate)
            $oForm->aInputs['relocate']['value'] = BX_DOL_URL_ROOT;

        $sFormCode = $oForm->getCode();

        BxDolTemplate::getInstance()->addJs(array('jquery.form.min.js'));
        
        $sJoinText = '';
        if (strpos($sParams, 'no_join_text') === false)
            $sJoinText = '<hr class="bx-def-hr bx-def-margin-sec-topbottom" /><div>' . _t('_sys_txt_login_description', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account')) . '</div>';

        $sAuth = '';
        if (strpos($sParams, 'no_auth_buttons') === false)
            $sAuth = $this->serviceMemberAuthCode();

        return $sCustomHtmlBefore . $sAuth . $sFormCode . $sCustomHtmlAfter . $sJoinText;

    }

}

/** @} */
