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

    public function getFormCallBackUrlAPI($sAction, $iId = 0)
    {
         return '/api.php?r=system/perfom_action_api/TemplServiceGrid/&params[]=&o=' . $this->_sObject . '&a=' . $sAction;
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $oForm = $this->_oModule->getFormObjectInvite();
        if(!$oForm)
            return $this->_getActionResult([]);

        $oForm->aInputs['text']['value'] = _t('_bx_invites_msg_invitation');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r'));
        $oForm->initChecker();

        $aResult = [];
        if($oForm->isSubmittedAndValid()) {
            $sResult = $this->_oModule->processFormObjectInvite($oForm);
            if($this->_bIsApi)
                $aResult = [bx_api_get_msg($sResult)];
            else
                $aResult = ['msg' => $sResult];
        }
        else {
            if($this->_bIsApi)
                $aResult = $this->getFormBlockAPI($oForm, $sAction);
            else
                $aResult = ['popup' => [
                    'html' => BxTemplFunctions::getInstance()->popupBox('_bx_invites_form_invite', _t('_bx_invites_form_invite'), $this->_oModule->_oTemplate->parseHtmlByName('popup_invite.html', [
                        'form_id' => $oForm->id,
                        'form' => $oForm->getCode(true),
                        'object' => $this->_sObject,
                        'action' => $sAction
                    ])), 
                    'options' => ['closeOnOuterClick' => true]
                ]];
        }

        return $this->_getActionResult($aResult);
    }
    
    public function performActionInfo()
    {
        $iId = $this->_getId();
        if(!$iId)
            return $this->_getActionResult([]);

        $aRequest = $this->_oModule->_oDb->getRequests(['type' => 'by_id', 'value' => $iId]);
        if(empty($aRequest) || !is_array($aRequest))
            return $this->_getActionResult([]);

        $sContent =  $this->_oModule->_oTemplate->getBlockRequestText($aRequest);

        $aResult = [];
        if($this->_bIsApi)
            $aResult = [bx_api_get_block('simple_list',  $sContent)];
        else
            $aResult = ['popup' => [
                'html' => BxTemplFunctions::getInstance()->transBox('bx-invites-info-popup', $sContent)
            ]];

        return $this->_getActionResult($aResult);
    }

    public function performActionInviteInfo()
    {
        $iId = $this->_getId();
        if(!$iId)
            return $this->_getActionResult([]);

        $aRequest = $this->_oModule->_oDb->getInvites(['type' => 'all', 'value' => $iId]);
        if(empty($aRequest) || !is_array($aRequest))
            return $this->_getActionResult([]);

        $sContent = $this->_oModule->_oTemplate->getBlockInviteInfo($aRequest);

        $aResult = [];
        if($this->_bIsApi)
            $aResult = [bx_api_get_block('simple_list',  $sContent)];
        else
            $aResult = ['popup' => [
                'html' => BxTemplFunctions::getInstance()->transBox('bx-invites-info-popup', $sContent)
            ]];

        return $this->_getActionResult($aResult);
    }
    
    public function performActionInvite($aParams = [])
    {
        $iProfileId = $this->_oModule->getProfileId();

        if(($mixedAllowed = $this->_oModule->isAllowedInvite($iProfileId)) !== true)
            return echoJson(['msg' => $mixedAllowed]);

        $aIds = $this->_getIds();
        if(!$aIds)
            return echoJson([]);

        $sText = _t('_bx_invites_msg_invitation');

        $iAffected = 0;
        $aIdsAffected = [];
        foreach($aIds as $iId) {
            $aRequest = $this->_oModule->_oDb->getRequests(['type' => 'by_id', 'value' => $iId]);
            if(empty($aRequest) || !is_array($aRequest))
                continue;
            
            $mixedResult = $this->_oModule->invite(BX_INV_TYPE_FROM_SYSTEM, [$aRequest['email']], $sText, false);
            if(empty($mixedResult) || !is_array($mixedResult))
                continue;

            $aInviteIds = array_keys($mixedResult);
            $this->_oModule->_oDb->attachInviteToRequest($iId, (int)array_shift($aInviteIds));

            $aIdsAffected[] = $iId;
            $iAffected++;
        }
        
        $sResult = $iAffected ? _t('_bx_invites_msg_invitation_sent', $iAffected) : _t('_bx_invites_err_invite');

        $aResult = [];
        if($this->_bIsApi)
            $aResult = [bx_api_get_block('simple_list',  [['text' => $sResult]])];
        else 
            $aResult = $iAffected ? ['grid' => $this->getCode(false), 'blink' => $aIdsAffected, 'msg' => $sResult] : ['msg' => $sResult];

        return $this->_getActionResult($aResult);
    }

    public function performActionDelete($aParams = [])
    {
        $iProfileId = $this->_oModule->getProfileId();

        if(($mixedAllowed = $this->_oModule->isAllowedDeleteRequest($iProfileId)) !== true)
            return echoJson(['msg' => $mixedAllowed]);

        $aIds = $this->_getIds();
        if(!$aIds)
            return echoJson([]);

        $oForm = BxDolForm::getObjectInstance($this->_oModule->_oConfig->getObject('form_request'), $this->_oModule->_oConfig->getObject('form_display_request_send'));

        $iAffected = 0;
        $aIdsAffected = [];
        foreach($aIds as $iId) {
            if(!$oForm->delete($iId))
                continue;

            $this->_oModule->isAllowedDeleteRequest($iProfileId, true);

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $aResult = [];
        if($iAffected) 
            $aResult = !$this->_bIsApi ? ['grid' => $this->getCode(false), 'blink' => $aIdsAffected] : [];
        else
            $aResult = ['msg' => _t('_bx_invites_err_delete_request')];

        return $this->_getActionResult($aResult);
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
        if($this->_bIsApi)
            return ['type' => 'time', 'data' => $mixedValue];

        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        $sStatus = _t('_bx_invites_request_status_new');

        if(in_array($mixedValue, [1, 2, 3])) {
            switch ($mixedValue) {
                case 1:
                case 2:
                    $iInvites = $this->_oModule->_oDb->getInvites(['type' => 'count_by_request', 'value' => $aRow['id']]);
                    $sStatus = _t($mixedValue == 1 ? '_bx_invites_request_status_invited' : '_bx_invites_request_status_seen') . ' (' . $iInvites . ')';
                    break;

                case 3:
                    $sStatus = _t('_bx_invites_request_status_joined');
                    break;
            }

            if(!$this->_bIsApi)
                $sStatus = $this->_oTemplate->parseLink('javascript:void(0)', $sStatus, [
                    'title' => bx_html_attribute(_t('_bx_invites_grid_action_title_adm_invite_info')),
                    'bx_grid_action_single' => 'invite_info',
                    'bx_grid_action_data' => $aRow['id']
                ]);
        }

        return parent::_getCellDefault($sStatus, $sKey, $aField, $aRow);
    }

    protected function _getCellJoinedAccount($mixedValue, $sKey, $aField, $aRow)
    {
        $sAccountInfo = "";
        if(isset($aRow["status"]) && $aRow["status"] == 3) {
            $iAccountId = $this->_oModule->_oDb->getInvites(['type' => 'account_by_request', 'value' => $aRow['id']]);
            if($this->_bIsApi) {
                $iProfileId = 0;
                if(($mixedProfileId = BxDolProfileQuery::getInstance()->getCurrentProfileByAccount($iAccountId, true)) !== false)
                    $iProfileId = (int)$mixedProfileId;

                return ['type' => 'profile', 'data' => BxDolProfile::getData($iProfileId)];
            }

            $sAccountInfo = $this->_oModule->_oTemplate->getProfilesByAccount($iAccountId);
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

    protected function _getId()
    {
        $iId = 0;

        if(($aIds = bx_get('ids')) !== false) {
            if(!$aIds || !is_array($aIds))
                return 0;

            $iId = (int)array_shift($aIds);
        }
        else if(($iId = bx_get('id')) !== false)
            $iId = (int)$iId;
        
        return $iId;
    }

    protected function _getIds()
    {
        $aIds = [];

        if(($aIds = bx_get('ids')) !== false) {
            if(!$aIds || !is_array($aIds))
                return [];

            $aIds = bx_process_input($aIds, BX_DATA_INT);
        }
        else if(($iId = bx_get('id')) !== false)
            $aIds = [(int)$iId];

        return $aIds;
    }
}

/** @} */
