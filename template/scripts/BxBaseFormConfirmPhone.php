<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

bx_import('BxTemplFormView');

class BxFormConfirmPhoneCheckerHelper extends BxDolFormCheckerHelper
{
    /**
     * Check if key exists or wrong format
     */
    function checkPhoneUniq($s)
    {
        $s = trim($s);
        if(!preg_match("/^\+[0-9\s]*$/", $s)) {
            return _t('_sys_form_confirm_phone_input_phone_error_format');
        }
        
        $oAccount = BxDolAccount::getInstance();
        if ($oAccount) { // user is logged in
            $aAccountInfo = $oAccount->getInfo();
            if ($s == $aAccountInfo['phone']) // don't check phone for uniq, if it wasn't changed
                return true;
        }
        
        return BxDolAccountQuery::getInstance()->getIdByPhone($s) ? _t('_sys_form_confirm_phone_input_phone_error_not_unique') : true;
    }
    
    function checkCodeExist($s)
    {
        $s = trim($s);
        if($s == '') {
            return _t('_sys_form_confirm_phone_input_code_error_empty');
        }
        
        $oSession = BxDolSession::getInstance();
        return $oSession->getValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE) != $s ? _t('_sys_form_confirm_phone_input_code_error_invalid', bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-phone')) . '?step=1') : true;
    }
}

/**
 * Phone Confirmation Form.
 */
class BxBaseFormConfirmPhone extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }

}

/** @} */
