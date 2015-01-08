<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     TridentModules
 * 
 * @{
 */

bx_import('BxDolProfile');
bx_import('BxBaseModProfileGridAdministration');

class BxAccntGridAdministration extends BxBaseModProfileGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_accounts';
        parent::__construct ($aOptions, $oTemplate);
    }

    public function performActionActivate()
    {
    	$this->_performActionEnable(true);
    }

	public function performActionSuspend()
    {
    	$this->_performActionEnable(false);
    }

    public function performActionResendCemail()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            return;
        }

        bx_import('BxDolAccount');
        $oAccount = BxDolAccount::getInstance();

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
			if($oAccount->sendConfirmationEmail($iId)) {
				$aIdsAffected[] = $iId;
        		$iAffected++;
			}

		$this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }

	protected function _performActionEnable($isChecked)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            return;
        }

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
        	if($this->_enable($iId, $isChecked)) {
        		$aIdsAffected[] = $iId;
        		$iAffected++;
        	}

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }

    protected function _enable($mixedId, $isChecked)
    {
    	$iAction = BX_PROFILE_ACTION_MANUAL;
    	$oProfile = BxDolProfile::getInstanceAccountProfile($mixedId);
    	return $isChecked ? $oProfile->activate($iAction) : $oProfile->suspend($iAction);
    }

    //--- Layout methods ---//
    protected function _getCellEmailConfirmed($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = (int)$mixedValue == 1 ? '_Yes' : '_No';
        return parent::_getCellDefault(_t($mixedValue), $sKey, $aField, $aRow);
    }

	protected function _getCellProfiles($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $this->_oModule->_oTemplate->getProfilesByAccount($aRow);

        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellLogged($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

	protected function _getContentInfo($iId)
    {
    	bx_import('BxDolAccount');
    	return BxDolAccount::getInstance($iId)->getInfo();
    }

	protected function _doDelete($iId, $aParams = array())
    {
    	bx_import('BxDolAccount');
    	return BxDolAccount::getInstance($iId)->delete(isset($aParams['with_content']) && $aParams['with_content'] === true);
    }
}

/** @} */
