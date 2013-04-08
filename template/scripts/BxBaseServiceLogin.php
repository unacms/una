<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * System service for login form functionality.
 */
class BxBaseServiceLogin extends BxDol {

    public function __construct() {
        parent::__construct();
    }

    public function serviceLoginForm ($sParams = '') {

        if (isLogged()) {
            return false;
        }

        // get all auth types
        $aAuthTypes = BxDolDb::getInstance()->fromCache('sys_objects_auths', 'getAll', 'SELECT * FROM `sys_objects_auths`');

        // define additional auth types
        if ($aAuthTypes) {

            $aAddInputEl[''] = _t('_Basic');

            // procces all additional menu's items
            foreach($aAuthTypes as $iKey => $aItems)
                $aAddInputEl[$aItems['Link']] = _t($aItems['Title']);

            $aAuthTypes = array(
                'type' => 'select',
                'caption' => _t('_Auth type'),
                'values' => $aAddInputEl,
                'value' => '',
                'attrs' => array ('onchange' => 'if (this.value) { location.href = "' . BX_DOL_URL_ROOT . '" + this.value }'),
            );

        } else {

            $aAuthTypes = array('type' => 'hidden');

        }
        
        bx_import ('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login');

        $sCustomHtmlBefore = '';
        $sCustomHtmlAfter = '';
        bx_alert('profile', 'show_login_form', 0, 0, array('oForm' => $oForm, 'sParams' => &$sParams, 'sCustomHtmlBefore' => &$sCustomHtmlBefore, 'sCustomHtmlAfter' => &$sCustomHtmlAfter, 'aAuthTypes' => &$aAuthTypes));

        $sFormCode = $oForm->getCode();

        $sJoinText = '';        
        if (strpos($sParams, 'no_join_text') === false) {
            bx_import('BxDolPermalinks');
            $sJoinText = '<div class="bx-def-margin-sec-top">' . _t('_sys_txt_login_description', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account')) . '</div>';
        }

        BxDolTemplate::getInstance()->addJs(array('jquery.form.js'));

        return $sCustomHtmlBefore . $sFormCode . $sCustomHtmlAfter . $sJoinText;

    }

}

/** @} */
