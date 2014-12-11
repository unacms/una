<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplFormView');

class BxFormForgotPasswordCheckerHelper extends BxDolFormCheckerHelper
{
    /**
     * Check if email is uniq.
     */
    function checkEmailExist ($s)
    {
        if (!$this->checkEmail($s))
            return false;

        bx_import('BxDolAccountQuery');
        return BxDolAccountQuery::getInstance()->getIdByEmail($s) ? true : _t('_sys_form_forgot_password_email_not_recognized');
    }

}

/**
 * Forgot Password Form.
 */
class BxBaseFormForgotPassword extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }

}

/** @} */
