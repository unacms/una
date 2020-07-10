<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxAccntGridAdministration extends BxBaseModProfileGridAdministration
{
    protected $_sFilter2Name;
	protected $_sFilter2Value;
	protected $_aFilter2Values;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_accounts';
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_aFilter1Values = array_merge($this->_aFilter1Values, array(
            'unconfirmed' => $CNF['T']['filter_item_unconfirmed'],
            'locked' => $CNF['T']['filter_item_locked'],
        ));

        $this->_sFilter2Name = 'filter2';
        $this->_aFilter2Values = array(
            'operators' => $CNF['T']['filter_item_operators']
        );

        $sFilter2 = bx_get($this->_sFilter2Name);
        if(!empty($sFilter2)) {
            $this->_sFilter2Value = bx_process_input($sFilter2);
            $this->_aQueryAppend[$this->_sFilter2Name] = $this->_sFilter2Value;
        }
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value))
            switch($this->_sFilter1Value) {
                case 'unconfirmed':
                    $sCnfnType = getParam('sys_account_confirmation_type');
                    switch($sCnfnType) {
                        case 'email':
                            $this->_aOptions['source'] .= " AND `ta`.`email_confirmed`='0'";
                            break;
                        case 'phone':
                            $this->_aOptions['source'] .= " AND `ta`.`phone_confirmed`='0'";
                            break;
                        case 'email_and_phone':
                            $this->_aOptions['source'] .= " AND (`ta`.`email_confirmed`='0' OR `ta`.`phone_confirmed`='0')";
                            break;
                    }
                    break;

                case 'locked':
                    $this->_aOptions['source'] .= " AND `ta`.`locked` = 1";
                    break;

                default:
        	    $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`status`=?", $this->_sFilter1Value);
            }

        if(!empty($this->_sFilter2Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `ta`.`role` & " . BX_DOL_ROLE_ADMIN ." = " . BX_DOL_ROLE_ADMIN);

        return parent::_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values) . $this->_getSearchInput();
    }
    
    public function getCode($isDisplayHeader = true)
    {
        return $this->_oModule->_oTemplate->getJsCode('main', array(
        	'aHtmlIds' => $this->_oModule->_oConfig->getHtmlIds()
        )) . parent::getCode($isDisplayHeader);
    }

    public function performActionActivate()
    {
    	$this->_performActionEnable(true);
    }

	public function performActionSuspend()
    {
    	$this->_performActionEnable(false);
    }

    public function performActionEditEmail()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return echoJson(array());

            $aIds = array($iId);
        }

        $iId = $aIds[0];
        $oAccount = BxDolAccount::getInstance($iId);
        if(!$oAccount)
            return echoJson(array());

        $aAccount = $oAccount->getInfo();
        $sAction = 'edit_email';

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ACCOUNT'], $CNF['OBJECT_FORM_ACCOUNT_DISPLAY_SETTINGS_EMAIL']);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&id=' . $iId;
        $oForm->initChecker($aAccount);

        if($oForm->isSubmittedAndValid()) {
            $sEmail = $oForm->getCleanValue('email');
            if(strcmp($aAccount['email'], $sEmail) !== 0 && BxDolAccountQuery::getInstance()->getIdByEmail($sEmail))
                return echoJson(array('msg' => _t('_sys_form_account_input_email_uniq_error_loggedin')));

            if($oForm->update($aAccount['id'])) {
                bx_alert('account', 'edited', $aAccount['id'], BxDolAccount::getInstance()->id(), array('display' => $CNF['OBJECT_FORM_ACCOUNT_DISPLAY_SETTINGS_EMAIL']));

                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
                
                $oAccount->doAudit($iId, '_sys_audit_action_account_email_changed', array('old_email' => $oAccount->getEmail(), 'new_email' => $sEmail));
            }
            else
                $aRes = array('msg' => _t('_sys_txt_error_account_update'));

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-account-edit-email-popup', _t('_bx_accounts_form_display_account_settings_email_popup'), $this->_oModule->_oTemplate->parseHtmlByName('edit_email.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }
    
    public function performActionAdd()
    {
      
        $CNF = &$this->_oModule->_oConfig->CNF;
        $sAction = 'add';
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ACCOUNT'], $CNF['OBJECT_FORM_ACCOUNT_DISPLAY_CREATE']);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
           
            $iAccountId = $oForm->insert();
            
            if (!$iAccountId) {
                $aRes = array('msg' => _t('_sys_txt_error_account_insert'));
            }
            else{
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iAccountId);
                
                $oBxTemplAccountForms = new BxTemplAccountForms();
                
                $iProfileId = $oBxTemplAccountForms->onAccountCreated($iAccountId, $oForm->isSetPendingApproval(), BX_PROFILE_ACTION_MANUAL, false);

                // perform action
                BxDolAccount::isAllowedCreate ($iProfileId, true);
                
                // check
                $aModulesProfile = array(); 
                $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
                foreach($aModules as $aModule) {
                    $oModule = BxDolModule::getInstance($aModule['name']);
                    if($oModule instanceof iBxDolProfileService && BxDolService::call($aModule['name'], 'act_as_profile') === true)
                        $aModulesProfile[] = $aModule;
                }

                $sDefaultProfileType = getParam('sys_account_default_profile_type');
                if(count($aModulesProfile) == 1)
                    $sProfileModule = $aModulesProfile[0]['name'];
                else if(!empty($sDefaultProfileType)) 
                    $sProfileModule = $sDefaultProfileType;

                if (getParam('sys_account_auto_profile_creation') && !empty($sProfileModule)) {
                    $oAccount = BxDolAccount::getInstance($iAccountId);
                    $aProfileInfo = BxDolService::call($sProfileModule, 'prepare_fields', array(array(
                        'author' => $iProfileId,
                        'name' => $oAccount->getDisplayName(),
                    )));
                    $a = BxDolService::call($sProfileModule, 'entity_add', array($iProfileId, $aProfileInfo));
                    if (isset($a['content']['profile_id']) && (int)$a['content']['profile_id'] > 0){
                        BxDolAcl::getInstance()->setMembership((int)$a['content']['profile_id'], MEMBERSHIP_ID_STANDARD);
                    }
                    echoJson($aRes);
                }
            }
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('bx-account-edit-email-popup', _t('_bx_accounts_form_display_account_create_popup'), $this->_oModule->_oTemplate->parseHtmlByName('create.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }
    
    public function performActionConfirm()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $oAccount = BxDolAccount::getInstance();

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId){
            if (BxDolAccount::isNeedConfirmEmail()){
			    if($oAccount->updateEmailConfirmed(true, true, $iId)) {
				    $aIdsAffected[] = $iId;
        		    $iAffected++;  
			    }
            }
            if (BxDolAccount::isNeedConfirmPhone()){
			    if($oAccount->updatePhoneConfirmed(true, $iId)) {
				    $aIdsAffected[] = $iId;
        		    $iAffected++;  
			    }
            }
        }
		echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }
    
    public function performActionResendCemail()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $oAccount = BxDolAccount::getInstance();

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
			if($oAccount->sendConfirmationEmail($iId)) {
				$aIdsAffected[] = $iId;
        		$iAffected++;  
			}

		echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }

    public function performActionResetPassword()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $iId = $aIds[0];
        $oAccount = BxDolAccount::getInstance($iId);
        if(!$oAccount)
            return echoJson(array());

        $aRes = array();
        if (BxDolAccount::getInstance()->updatePassword(genRndPwd(), $iId)){
            $sPopupId = $this->_oModule->_oConfig->getHtmlIds('password_popup');
            $sPopupTitle = _t('_bx_accounts_form_display_account_settings_password_popup');
            $sPopupContent = $this->_oModule->_oTemplate->parseHtmlByName('reset_password.html', array(
                'js_object' => $this->_oModule->_oConfig->getJsObject('main'),
                'html_id_text' => $this->_oModule->_oConfig->getHtmlIds('password_text'),
                'html_id_button' => $this->_oModule->_oConfig->getHtmlIds('password_button'),
                'password' => $sPwd,
            ));

            $aRes = array('popup' => BxTemplStudioFunctions::getInstance()->popupBox($sPopupId, $sPopupTitle, $sPopupContent));
        }
        else 
            $aRes = array('msg' => _t('_bx_accnt_grid_action_err_perform'));

        return echoJson($aRes);
    }
    
    public function performActionUnlockAccount()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }
		$oAccountQuery = BxDolAccountQuery::getInstance();
		foreach($aIds as $iId){
			$oAccount = BxDolAccount::getInstance($iId);
			if(!$oAccount)
				continue;
			if ($oAccount->isLocked()){
				$oAccountQuery->unlockAccount($iId);
			}
            
            $oAccount->doAudit($iId, '_sys_audit_action_account_unlock');
		}
		$aRes = array('grid' => $this->getCode(false), 'blink' => $aIds);
        return echoJson($aRes);
    }

	public function performActionMakeOperator()
    {
    	$this->_performActionChangeRole(3);
    }

	public function performActionUnmakeOperator()
    {
    	$this->_performActionChangeRole(1);
    }

	protected function _performActionChangeRole($iRole)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
			if($this->_oModule->_oDb->updateAccount(array('role' => $iRole), array('id' => $iId))) {
				$aIdsAffected[] = $iId;
        		$iAffected++;
                
                $oAccount = BxDolAccount::getInstance($iId);
                $oAccount->doAudit($iId, '_sys_audit_action_account_change_role_to_' . $iRole);
			}

		echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }

	protected function _performActionEnable($isChecked)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            return;
        }
        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
        	if($this->_enable($iId, $isChecked)) {
        		$aIdsAffected[] = $iId;
        		$iAffected++;
                
                $oAccount = BxDolAccount::getInstance($iId);
                $oAccount->doAudit($iId, '_sys_audit_action_account_' . $isChecked ? 'activated' : 'suspended');
        	}

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_perform'])));
    }

    protected function _enable($mixedId, $isChecked)
    {
    	$oProfile = BxDolProfile::getInstanceAccountProfile($mixedId);
    	if(!$oProfile)
    	    return false;

        $iAction = BX_PROFILE_ACTION_MANUAL;
        $sMethod = $isChecked ? 'activate' : 'suspend';
        if(!$oProfile->$sMethod($iAction, 0 ,false))
            return false;

        sendMailTemplate('t_ChangeStatusAccount' . ucfirst($sMethod), $oProfile->getAccountObject()->id(), $oProfile->id(), array(), BX_EMAIL_SYSTEM);
        
        $aProfiles = $oProfile->getAccountObject()->getProfiles();
        foreach($aProfiles as $aProfile){
            BxDolProfile::getInstance($aProfile['id'])->$sMethod($iAction, 0 ,false);
        }
        
    	return true;
    }

    //--- Layout methods ---//
    protected function _getActionEditEmail($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '';
    }
    
    protected function _getCellEmailConfirmed($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = (int)$mixedValue == 1 ? '_Yes' : '_No';
        return parent::_getCellDefault(_t($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionResetPassword($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '';
    }
    
    protected function _getActionUnlockAccount($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '';
    }

    protected function _getCellName($mixedValue, $sKey, $aField, $aRow)
    {
        $oAccount = BxDolAccount::getInstance($aRow['id']);
        if ($oAccount)
            $s = ($aRow['locked'] == 1 ? $this->_oTemplate->parseIcon("lock col-red1") . ' ' : '') . $oAccount->getDisplayName();
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }
    
	protected function _getCellProfiles($mixedValue, $sKey, $aField, $aRow)
    {
        $s = $this->_oModule->_oTemplate->getProfilesByAccount($aRow);

        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellLogged($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = !empty($mixedValue) ? bx_time_js($mixedValue) : _t('_sys_not_available');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    } 
    
    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = !empty($mixedValue) ? bx_time_js($mixedValue) : _t('_sys_not_available');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    } 
    
    protected function _getCellIsConfirmed($mixedValue, $sKey, $aField, $aRow)
    {
        $oAccount = BxDolAccount::getInstance($aRow['id']);
        $s = "";
        if ($oAccount)
            $s = $oAccount->getCurrentConfirmationStatusValue();
        return parent::_getCellDefault(_t('_bx_accnt_grid_confirmation_status_' . $s), $sKey, $aField, $aRow);
    }

    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

	protected function _getContentInfo($iId)
    {
    	return BxDolAccountQuery::getInstance()->getInfoById($iId);
    }

	protected function _doDelete($iId, $aParams = array())
    {
    	return BxDolAccount::getInstance($iId)->delete(isset($aParams['with_content']) && $aParams['with_content'] === true);
    }
    
    

    protected function _addJsCss()
    {
        parent::_addJsCss();
        
        $this->_oTemplate->addJs(array(
            'main.css'
        ));

        $this->_oTemplate->addJs(array(
        	'jquery.form.min.js',
            'clipboard.min.js',
            'main.js'
        ));
    }

    protected function _isVisibleGrid($a)
    {
        if(isAdmin())
            return true;
        
        return parent::_isVisibleGrid($a);
    }
}

/** @} */
