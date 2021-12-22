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

define('BX_INV_FILTER_STATUS_NEW', 0);
define('BX_INV_FILTER_STATUS_INVITED', 1);
define('BX_INV_FILTER_STATUS_SEEN', 2);
define('BX_INV_FILTER_STATUS_JOINED', 3);

class BxInvGridRequests extends BxTemplGrid
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

        $this->_aQueryReset = array('order_field', 'order_dir', $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);
        
        $this->_sModule = 'bx_invites';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
        
        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
            '' => _t('_bx_invites_request_status_for_filter_all'),
            BX_INV_FILTER_STATUS_NEW => _t('_bx_invites_request_status_for_filter_new'),
            BX_INV_FILTER_STATUS_INVITED => _t('_bx_invites_request_status_for_filter_invited'),
            BX_INV_FILTER_STATUS_SEEN => _t('_bx_invites_request_status_for_filter_seen'),
            BX_INV_FILTER_STATUS_JOINED => _t('_bx_invites_request_status_for_filter_joined')
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
        $sTableRequests = $CNF['TABLE_REQUESTS'];
        
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);
       
        $sFilterSql = "";
        if(isset($this->_sFilter1Value) && $this->_sFilter1Value != ''){
            if ($this->_sFilter1Value == BX_INV_FILTER_STATUS_INVITED){
                $sFilterSql = " AND " . $sTableRequests . ".`status` IN (" . BX_INV_FILTER_STATUS_INVITED . ', ' . BX_INV_FILTER_STATUS_SEEN . ', ' . BX_INV_FILTER_STATUS_JOINED . ')';
            }
            else{
                $sFilterSql = " AND " . $sTableRequests . ".`status` = " . $this->_sFilter1Value;
            }
        }
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString($sFilterSql);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    protected function _getDataSqlOrderClause ($sOrderByFilter, $sOrderField, $sOrderDir, $bFieldsOnly = false)
    {
        return " ORDER BY `status` ASC, `date` DESC";
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
    
    public function performActionInfo()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $aRequest = $this->_oModule->_oDb->getRequests(array('type' => 'by_id', 'value' => (int)array_shift($aIds)));
        if(empty($aRequest) || !is_array($aRequest)){
            echoJson(array());
            exit;
        }

        $sContent = BxTemplFunctions::getInstance()->transBox('bx-invites-info-popup', $this->_oModule->_oTemplate->getBlockRequestText($aRequest));

        echoJson(array('popup' => array('html' => $sContent)));
    }
    
    public function performActionInviteInfo()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $aRequest = $this->_oModule->_oDb->getInvites(array('type' => 'all', 'value' => (int)array_shift($aIds)));
        if(empty($aRequest) || !is_array($aRequest)){
            echoJson(array());
            exit;
        }

        $sContent = BxTemplFunctions::getInstance()->transBox('bx-invites-info-popup', $this->_oModule->_oTemplate->getBlockInviteInfo($aRequest));

        echoJson(array('popup' => array('html' => $sContent)));
    }
    
    public function performActionInvite($aParams = array())
    {
        $iProfileId = $this->_oModule->getProfileId();

        $mixedAllowed = $this->_oModule->isAllowedInvite($iProfileId);
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

        $sText = _t('_bx_invites_msg_invitation');

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $aRequest = $this->_oModule->_oDb->getRequests(array('type' => 'by_id', 'value' => $iId));
            if(empty($aRequest) || !is_array($aRequest))
                continue;
            
            $iInviteId = -1;
            $mixedResult = $this->_oModule->invite(BX_INV_TYPE_FROM_SYSTEM, $aRequest['email'], $sText);
            if(empty($mixedResult) || !is_array($mixedResult))
                continue;

            $iInviteId = (int)array_shift($mixedResult);
            $this->_oModule->onInvite($iProfileId);
            $this->_oModule->_oDb->attachInviteToRequest($iId, $iInviteId);
            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected, 'msg' => _t('_bx_invites_msg_invitation_sent', $iAffected)) : array('msg' => _t('_bx_invites_err_invite')));
    }
    
    public function performActionDelete($aParams = array())
    {
        $iProfileId = $this->_oModule->getProfileId();

        $mixedAllowed = $this->_oModule->isAllowedDeleteRequest($iProfileId);
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

        $oForm = BxDolForm::getObjectInstance($this->_oModule->_oConfig->getObject('form_request'), $this->_oModule->_oConfig->getObject('form_display_request_send'));

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            if(!$oForm->delete($iId))
                continue;

            $this->_oModule->isAllowedDeleteRequest($iProfileId, true);

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_bx_invites_err_delete_request')));
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

    protected function _getCellNip($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(long2ip($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        $sStatus = _t('_bx_invites_request_status_new');
        switch ($mixedValue) {
            case 1:
            case 2:
                $sLinkHtml = $this->_oTemplate->parseLink('javascript:void(0)', $this->_oModule->_oDb->getInvites(array('type' => 'count_by_request', 'value' => $aRow['id'])), array(
                    'title' => _t('_bx_invites_grid_action_title_adm_invite_info'),
                    'bx_grid_action_single' => 'invite_info',
                    'bx_grid_action_data' => $aRow['id']
                ));
                $sStatus = ($mixedValue == 1 ? _t('_bx_invites_request_status_invited') : _t('_bx_invites_request_status_seen')) . ' (' . $sLinkHtml . ')';
                break;
            case 3:
                $sStatus = _t('_bx_invites_request_status_joined') . ' (' . bx_time_js($this->_oModule->_oDb->getInvites(array('type' => 'date_joined_by_request', 'value' => $aRow['id']))) . ')';
                break;
        }
        return parent::_getCellDefault($sStatus, $sKey, $aField, $aRow);
    }
    
    protected function _getCellJoinedAccount($mixedValue, $sKey, $aField, $aRow)
    {
        $sAccountInfo = "";
        if(isset($aRow["status"]) && $aRow["status"] == 3){
            $sAccountInfo = $this->_oModule->_oTemplate->getProfilesByAccount($this->_oModule->_oDb->getInvites(array('type' => 'account_by_request', 'value' => $aRow['id'])));
        }
        return parent::_getCellDefault($sAccountInfo, $sKey, $aField, $aRow);
    }
    
    protected function _getActionInvite($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(isset($aRow["status"]) && $aRow["status"] == 3)
            return '';
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionInviteInfo ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        return '';
    }
    
}

/** @} */
