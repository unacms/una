<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxFormAccountCheckerHelper extends BxDolFormCheckerHelper
{
    /**
     * Password confirmation check.
     */
    function checkPasswordConfirm ($s)
    {
        return $s == bx_process_input(bx_get(BxTemplFormAccount::$FIELD_PASSWORD));
    }
    
    function checkPassword ($s, $r)
    {
        $bValid = parent::checkPreg($s, $r);
        
        if (!$bValid)
            return $bValid;
        
        $oAccount = BxDolAccount::getInstance();
        if ($oAccount){
            $aPasswords = BxDolAccountQuery::getInstance()->getLastPasswordLog($oAccount->id());
            $bUsed = false;
            foreach ($aPasswords as $aPassword){
                if($aPassword['password'] == encryptUserPwd($s, $aPassword['salt']))
                    $bUsed = true;
            }

            if ($bUsed)
                return _t('_sys_form_account_input_password_error_old_used');
        }
        
        return true;
    }

    /**
     * Password confirmation check.
     */
    function checkPasswordCurrent ($s)
    {
        $oAccount = BxDolAccount::getInstance();
        if (!$oAccount)
            return false;

        $aInfo = $oAccount->getInfo();
		$sPassCheck = encryptUserPwd($s, $aInfo['salt']);
		
		// regenerate password using another encrypt function if necessary
		bx_alert('system', 'encrypt_password_after', 0, false, array(
            'info' => $aInfo,
			'pwd' => $s,
            'password' => &$sPassCheck,
        ));
		
		return $aInfo['password'] == $sPassCheck;
    }
    
    /**
     * Check if phone is exists.
     */
    function checkPhoneExist ($s)
    {
        $s = trim($s);
        if(!preg_match("/^\+[0-9\s]*$/", $s)) {
            return _t('_sys_form_forgot_password_phone_error_format');
        }

        return BxDolAccountQuery::getInstance()->getIdByPhone($s) ? _t('_sys_form_account_input_phone_uniq_error_loggedin') : true;
    }

    /**
     * Check if email is uniq.
     */
    function checkEmailUniq ($s)
    {
    	$s = trim($s);

        if (!$this->checkEmail($s))
            return false;

        $oAccount = BxDolAccount::getInstance();
        if ($oAccount) { // user is logged in
            $aAccountInfo = $oAccount->getInfo();
            if ($s == $aAccountInfo['email']) // don't check email for uniq, if it wasn't changed
                return true;
            return BxDolAccountQuery::getInstance()->getIdByEmail($s) ? _t('_sys_form_account_input_email_uniq_error_loggedin') : true;
        }

        return BxDolAccountQuery::getInstance()->getIdByEmail($s) ? _t('_sys_form_account_input_email_uniq_error', bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password'))) : true;
    }
}

/**
 * Create/Edit Account Form.
 */
class BxBaseFormAccount extends BxTemplFormView
{
	static $FIELD_EMAIL = 'email';
    static $FIELD_PASSWORD = 'password';
    static $FIELD_SALT = 'salt';
    static $FIELD_ADDED = 'added';
    static $FIELD_CHANGED = 'changed';
    static $FIELD_IP = 'ip';
    static $FIELD_REFERRED = 'referred';
	static $FIELD_PHONE = 'phone';

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

        if (isLogged() || !$this->isSubmitted()) return true; // exit in case it is an account edit or form has not been submitted yet

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

    public function setPendingApproval($b)
    {
        return ($this->_bSetPendingApproval = $b);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
    	$sEmail = isset($aValsToAdd[self::$FIELD_EMAIL]) ? $aValsToAdd[self::$FIELD_EMAIL] : $this->getCleanValue(self::$FIELD_EMAIL);
    	$sEmail = trim(strtolower($sEmail));
		
		$sPhone = isset($aValsToAdd[self::$FIELD_PHONE]) ? $aValsToAdd[self::$FIELD_PHONE] : $this->getCleanValue(self::$FIELD_PHONE);
    	$sPhone = trim(strtolower($sPhone));
    	
        $sPwd = isset($aValsToAdd[self::$FIELD_PASSWORD]) ? $aValsToAdd[self::$FIELD_PASSWORD] : $this->getCleanValue(self::$FIELD_PASSWORD);
        $sSalt = genRndSalt();
        $sPasswordHash = encryptUserPwd($sPwd, $sSalt);

        $aValsToAdd = array_merge($aValsToAdd, array (
        	self::$FIELD_EMAIL => $sEmail, 
            self::$FIELD_PASSWORD => $sPasswordHash,
            self::$FIELD_SALT => $sSalt,
            self::$FIELD_ADDED => time(),
            self::$FIELD_CHANGED => time(),
            self::$FIELD_IP => getVisitorIP(),
            self::$FIELD_REFERRED => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			self::$FIELD_PHONE => $sPhone,
        ));
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($val, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $sPwd = $this->getCleanValue(self::$FIELD_PASSWORD);
        if ($sPwd) {
            $sSalt = genRndSalt();
            $sPasswordHash = encryptUserPwd($sPwd, $sSalt);
            
            $oAccount = BxDolAccount::getInstance($val, true);
            $iPasswordExpired = $oAccount->getPasswordExpiredDateByAccount($val);
            
            BxDolAccountQuery::getInstance()->logPassword($val);
            BxDolAccountQuery::getInstance()->updatePasswordExpired($val, $iPasswordExpired);
        }

        $aValsToAdd = array_merge(
            $aValsToAdd,
            array (self::$FIELD_CHANGED => time()),
            $sPwd ? array (self::$FIELD_PASSWORD => $sPasswordHash, self::$FIELD_SALT => $sSalt) : array()
        );
        
        $bResult = parent::update ($val, $aValsToAdd, $aTrackTextFieldsChanges);
        
        if ($bResult){
            $oAccount = BxDolAccount::getInstance($val, true);
            if ($oAccount) { 
                $aAccountInfo = $oAccount->getInfo();
                bx_alert('account', 'change_receive_news', 0, false, array('account_id' => $val, 'old_value' => $aAccountInfo['receive_news'], 'new_value' => $this->getCleanValue('receive_news'), 'email' => $aAccountInfo['email']));
            }
        }
        return $bResult;
    }

	protected function genCustomInputAgreement ($aInput)
    {
    	$oPermalink = BxDolPermalinks::getInstance();
        return '<div>' . _t('_sys_form_account_input_agreement_value', bx_absolute_url($oPermalink->permalink('page.php?i=terms')), bx_absolute_url($oPermalink->permalink('page.php?i=privacy'))) . '</div>';
    }
    
    protected function _setCustomError ($s)
    {
        $this->aInputs['do_submit']['error'] = $s;
    }
}

/** @} */
