<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 * 
 * @{
 */
define('BX_INV_FILTER_STATUS_ACCEPTED', 1);
define('BX_INV_FILTER_STATUS_NOT_ACCEPTED', 2);

class BxInvGridInvites extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;
    protected $_sParamsDivider;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sModule = 'bx_invites';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
       
        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
            '' => _t('_bx_invites_invites_status_for_filter_all'),
            BX_INV_FILTER_STATUS_ACCEPTED => _t('_bx_invites_invites_status_for_filter_accepted'),
            BX_INV_FILTER_STATUS_NOT_ACCEPTED => _t('_bx_invites_invites_status_for_filter_not_accepted'),
        );
        
        $sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1Value;
        }
        $this->_sParamsDivider = '#-#';
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        $sTableInvites = $CNF['TABLE_INVITES'];
        
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);
        
        $sFilterSql = "";
        if(isset($this->_sFilter1Value) && $this->_sFilter1Value != ''){
            if ($this->_sFilter1Value == BX_INV_FILTER_STATUS_ACCEPTED){
                $sFilterSql = " AND " . $sTableInvites . ".`joined_account_id` IS NOT NULL";
            }
            else{
                $sFilterSql = " AND " . $sTableInvites . ".`joined_account_id` IS NULL";
            }
        }
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString($sFilterSql);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    public function performActionAdd()
    {
        $sAction = 'add';
        $oForm = $this->_oModule->getFormObjectInvite();
        if (!$oForm)
            return '';

        $oForm->aInputs['text']['value'] = _t('_bx_invites_msg_invitation');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r'));
        $oForm->initChecker();
        
        if($oForm->isSubmittedAndValid()) {
            $sResult = $this->_oModule->processFormObjectInvite($oForm);
            $aRes = array('msg' => $sResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox('_bx_invites_form_invite', _t('_bx_invites_form_invite'), $this->_oModule->_oTemplate->parseHtmlByName('popup_invite.html', array(
                'form_id' => $oForm->id,
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));
            echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => true))));
        }
    }
    
    public function performActionDelete($aParams = array())
    {
        $iProfileId = $this->_oModule->getProfileId();

        $mixedAllowed = $this->_oModule->isAllowedDeleteInvite($iProfileId);
        if($mixedAllowed !== true) {
            echoJson(array('msg' => $mixedAllowed));
            exit;
        }

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            if(!$this->_oModule->_oDb->deleteInvites(array('id' => $iId)))
                continue;

            $this->_oModule->isAllowedDeleteInvite($iProfileId, true);

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_bx_invites_err_delete_invite')));
    }
    
    protected function _getFilterControls ()
    {
        parent::_getFilterControls();
        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }
    
    protected function _getSearchInput()
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('main');
        $aInputSearch = array(
            'type' => 'text',
            'name' => 'search',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup focusout\'); ' . $sJsObject . '.onChangeFilter(this)',
                'onBlur' => 'javascript:' . $sJsObject . '.onChangeFilter(this)',
            )
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputSearch);
    }
    
    protected function _getFilterSelectOne($sFilterName, $sFilterValue, $aFilterValues)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

        $CNF = &$this->_oModule->_oConfig->CNF;
        $sJsObject = $this->_oModule->_oConfig->getJsObject('main');

        foreach($aFilterValues as $sKey => $sValue)
            $aFilterValues[$sKey] = _t($sValue);

        $aInputModules = array(
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $sFilterValue,
            'values' => $aFilterValues
        );

        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }
    
    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellDateSeen($mixedValue, $sKey, $aField, $aRow)
    {
        if ($mixedValue == 'undefined')
            return parent::_getCellDefault('', $sKey, $aField, $aRow);
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellDateJoined($mixedValue, $sKey, $aField, $aRow)
    {
        if ($mixedValue == 'undefined')
            return parent::_getCellDefault('', $sKey, $aField, $aRow);
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellWhoSend($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = BxDolProfile::getInstanceMagic($aRow['profile_id']);
        if ($oProfile)
            return $this->_getProfileCell($oProfile, $sKey, $aField, $aRow);
       
        return parent::_getCellDefault('', $sKey, $aField, $aRow);
    }
    
    protected function _getCellJoinedAccount($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['joined_account_id'] == '')
            return parent::_getCellDefault('', $sKey, $aField, $aRow);
        
        $oProfile = BxDolProfile::getInstanceAccountProfile($aRow['joined_account_id']);
        if ($oProfile)
            return $this->_getProfileCell($oProfile, $sKey, $aField, $aRow);
        
        return parent::_getCellDefault('', $sKey, $aField, $aRow);
    }
    
    protected function _getCellRequest($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['request_id'] == '')
            return parent::_getCellDefault('', $sKey, $aField, $aRow);
        
        $aRequest = $this->_oModule->_oDb->getRequests(array('type' => 'by_id', 'value' => $aRow['request_id']));
        if ($aRequest || true){
            $CNF = &$this->_oModule->_oConfig->CNF;
            $sLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_REQUESTS']);
            $sLink = bx_append_url_params($sLink, array('filter' => $aRequest['email']));
            $sAddon = $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $sLink,
                    'title' => _t('_bx_invites_grid_txt_view_request'),
                    'content' => _t('_bx_invites_grid_txt_view_request'),
                    'class' => ''
            ));
            return parent::_getCellDefault($sAddon, $sKey, $aField, $aRow);
        }
        return parent::_getCellDefault('', $sKey, $aField, $aRow);
    }
    
    protected function _getProfileCell($oProfile, $sKey, $aField, $aRow)
    {
    	$sProfile = $oProfile->getDisplayName();

        $oAcl = BxDolAcl::getInstance();

    	$sAccountEmail = '';
    	$sManageAccountUrl = '';
    	if($oProfile && $oProfile instanceof BxDolProfile && $oAcl->isMemberLevelInSet(128)) {
            $sAccountEmail = $oProfile->getAccountObject()->getEmail();
            $sManageAccountUrl = $this->_getManageAccountUrl($sAccountEmail);
    	}

        $sAddon = '';
        if(!empty($sManageAccountUrl))
            $sAddon = $this->_oTemplate->parseHtmlByName('account_link.html', array(
                'href' => $sManageAccountUrl,
                'title' => _t('_bx_invites_grid_txt_account_manager'),
                'content' => $sAccountEmail,
                'class' => 'bx-def-font-grayed'
            ));

        $mixedValue = $oProfile->getUnit(0, array('template' => array('vars' => array('addon' => $sAddon))));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getManageAccountUrl($sFilter = '')
    {
    	$sModuleAccounts = 'bx_accounts';
    	if(!BxDolModuleQuery::getInstance()->isEnabledByName($sModuleAccounts))
    		return '';

		$oModuleAccounts = BxDolModule::getInstance($sModuleAccounts);
		if(!$oModuleAccounts || empty($oModuleAccounts->_oConfig->CNF['URL_MANAGE_ADMINISTRATION']))
			return 'cccc';

		$sLink = $oModuleAccounts->_oConfig->CNF['URL_MANAGE_ADMINISTRATION'];

		$sLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sLink);
		
		if(!empty($sFilter))
			$sLink = bx_append_url_params($sLink, array('filter' => $sFilter));

		return $sLink;
    }
}

/** @} */
