<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplFormView');

class BxFormAccountCheckerHelper extends BxDolFormCheckerHelper
{
    /**
     * Password confirmation check.
     */
    function checkPasswordConfirm ($s)
    {
        return $s == bx_process_input(bx_get(BxTemplFormAccount::$FIELD_PASSWORD));
    }

    /**
     * Password confirmation check.
     */
    function checkPasswordCurrent ($s)
    {

        bx_import('BxDolAccount');
        $oAccount = BxDolAccount::getInstance();
        if (!$oAccount)
            return false;

        $aInfo = $oAccount->getInfo();

        return $aInfo['password'] == encryptUserPwd($s, $aInfo['salt']);
    }

    /**
     * Check if email is uniq.
     */
    function checkEmailUniq ($s)
    {
        if (!$this->checkEmail($s))
            return false;

        bx_import('BxDolAccount');
        bx_import('BxDolPermalinks');

        $oAccount = BxDolAccount::getInstance();
        if ($oAccount) { // user is logged in
            $aAccountInfo = $oAccount->getInfo();
            if ($s == $aAccountInfo['email']) // don't check email for uniq, if it wasn't changed
                return true;
            return BxDolAccountQuery::getInstance()->getIdByEmail($s) ? _t('_sys_form_account_input_email_uniq_error_loggedin') : true;
        }

        return BxDolAccountQuery::getInstance()->getIdByEmail($s) ? _t('_sys_form_account_input_email_uniq_error', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password')) : true;
    }

}

/**
 * Create/Edit Account Form.
 */
class BxBaseFormAccount extends BxTemplFormView
{
    static $FIELD_PASSWORD = 'password';
    static $FIELD_SALT = 'salt';
    static $FIELD_ADDED = 'added';
    static $FIELD_CHANGED = 'changed';

    protected $_bSetPendingApproval = false;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
        $this->_bSetPendingApproval = !(bool)getParam('sys_account_autoapproval');
    }

    function isValid ()
    {
        if (!parent::isValid ())
            return false;

        $sErrorMsg = '';
        bx_alert('account', 'check_join', 0, false, array('error_msg' => &$sErrorMsg, 'email' => $this->getCleanValue('email'), 'approve' => &$this->_bSetPendingApproval));
        if ($sErrorMsg)
            $this->_setCustomError ($sErrorMsg);

        return $sErrorMsg ? false : true;
    }

    public function isSetPendingApproval()
    {
        return $this->_bSetPendingApproval;
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $sPwd = $this->getCleanValue(self::$FIELD_PASSWORD);
        $sSalt = genRndSalt();
        $sPasswordHash = encryptUserPwd($sPwd, $sSalt);

        $aValsToAdd = array_merge($aValsToAdd, array (
            self::$FIELD_PASSWORD => $sPasswordHash,
            self::$FIELD_SALT => $sSalt,
            self::$FIELD_ADDED => time(),
            self::$FIELD_CHANGED => time(),
        ));
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($val, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $sPwd = $this->getCleanValue(self::$FIELD_PASSWORD);
        if ($sPwd) {
            $sSalt = genRndSalt();
            $sPasswordHash = encryptUserPwd($sPwd, $sSalt);
        }

        $aValsToAdd = array_merge(
            $aValsToAdd,
            array (self::$FIELD_CHANGED => time()),
            $sPwd ? array (self::$FIELD_PASSWORD => $sPasswordHash, self::$FIELD_SALT => $sSalt) : array()
        );
        return parent::update ($val, $aValsToAdd, $aTrackTextFieldsChanges);
    }

    protected function _setCustomError ($s)
    {
        $this->aInputs['do_submit']['error'] = $s;
    }
}

/** @} */
