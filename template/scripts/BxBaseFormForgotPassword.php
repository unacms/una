<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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
