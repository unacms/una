<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBasePrivacyFormGroupCustom extends BxTemplFormView
{
    protected $_iProfileId;
    protected $_iContentId;

    protected $_sObject;
    protected $_oObject;

    protected $_iGroupId;
    protected $_aGroupSettings;
    
    protected $_iGroupCustomId;
    protected $_aGroupCustomInfo;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_iProfileId = bx_get_logged_profile_id();
        $this->_iContentId = 0;

        $this->_sObject = '';
        $this->_oObject = null;

        $this->_iGroupId = 0;
        $this->_aGroupSettings = array();

        $this->_iGroupCustomId = 0;
        $this->_aGroupCustomInfo = array();

        switch($this->aParams['display']) {
            case 'sys_privacy_group_custom_members':
                $this->aInputs['action']['value'] = 'select_members';
                break;

            case 'sys_privacy_group_custom_memberships':
                $this->aInputs['action']['value'] = 'select_memberships';
                break;
        }
    }

    public function initChecker($aValues = array(), $aSpecificValues = array())
    {
        $this->initData($aValues);

        if(isset($this->aInputs['memberships']) && $this->_oObject->isAllowedMemberships($this->_iProfileId)) {
            $aSelected = array();
            if(!empty($this->_aGroupCustomInfo) && is_array($this->_aGroupCustomInfo))
                $this->aInputs['memberships']['value'] = $this->_aGroupCustomInfo['items'];

            $aLevels = BxDolAcl::getInstance()->getMemberships(false, true, true, true);
            if(!empty($aLevels) && is_array($aLevels)) {
                $aLevelsValues = [];
                foreach($aLevels as $iId => $sTitle)
                    $aLevelsValues[] = array('key' => $iId, 'value' => $sTitle);

                $this->aInputs['memberships']['values'] = $aLevelsValues;
            }
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function getGroupId()
    {
        return $this->_iGroupId;
    }

    public function getGroupCustomId()
    {
        return $this->_iGroupCustomId;
    }

    public function getElementGroupCustom($aParams = array())
    {
        $this->initData($aParams);

        $sName = 'sys-pgc-' . str_replace('_', '-', $this->_sObject);
        $iValue = !empty($aParams['value']) ? (int)$aParams['value'] : $this->_iGroupCustomId;

        $sItemsName = $sName . '-items';
        $aItemsValue = array();
        if(!empty($aParams['value_items']) && is_array($aParams['value_items']))
            $aItemsValue = $aParams['value_items'];
        else if($this->_iGroupCustomId)
            $aItemsValue = $this->_aGroupCustomInfo['items'];

        $sMethodItemsValue = 'getElementGroupCustomValue' . bx_gen_method_name($this->_aGroupSettings['name']);
        if(!empty($aItemsValue) && method_exists($this, $sMethodItemsValue))
            $aItemsValue = $this->$sMethodItemsValue($sItemsName, $aItemsValue);

        $aInput = array(
            'type' => 'hidden',
            'name' => $sName . '-id',
            'caption' => '',
            'value' => $iValue,
            'db' => array(
                'pass' => 'Int'
            )
        );

        $aInputItems = array(
            'type' => 'custom',
            'name' => $sItemsName,
            'caption' => '',
            'value' => $aItemsValue,
            'ajax_get_suggestions' => BX_DOL_URL_ROOT . bx_append_url_params('privacy.php', array(
                'object' => $this->_sObject,
                'action' => $this->_aGroupSettings['uri_get_items'],
                'group' => $this->_iGroupId
            )),
            'attrs' => array(
                'disabled' => 'disabled'
            )
        );

        return $this->oTemplate->parseHtmlByName('privacy_group_custom.html', [
            'js_object' => $this->_oObject->getJsObjectName(),
            'html_id' => $sName,
            'input_id' => $this->genInputStandard($aInput),
            'input_items' => $this->genCustomInputUsernamesSuggestions($aInputItems)
        ]);
    }

    protected function getElementGroupCustomValueMembershipsSelected($sName, $aItems)
    {
        $oAcl = BxDolAcl::getInstance();

        $aTmplVarsItems = [];
        foreach($aItems as $iItem) {
            $aItemInfo = $oAcl->getMembershipInfo($iItem);
            if(empty($aItemInfo) || !is_array($aItemInfo))
                continue;

            $aTmplVarsItems[] = [
                'name' => $sName,
                'value' => $iItem,
                'title' => _t($aItemInfo['name'])
            ];
        }

        return $this->oTemplate->parseHtmlByName('privacy_memberships_value.html', [
            'bx_repeat:vals' => $aTmplVarsItems
        ]);
    }

    protected function genCustomInputSearch($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . bx_append_url_params('privacy.php', array(
            'object' => $this->_sObject,
            'action' => 'users_list',
            'group' => $this->_iGroupId
        ));

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    protected function genCustomInputList($aInput)
    {
        $iProfileId = bx_get_logged_profile_id();

        $oConnection = BxDolConnection::getObjectInstance($this->_aGroupSettings['connection']);
        if(!$oConnection)
            return '';

        $aConnectedIds = $oConnection->getConnectedContent($iProfileId, true);

        $aSelected = array();
        if(!empty($this->_aGroupCustomInfo) && is_array($this->_aGroupCustomInfo))
            $aSelected = $this->_aGroupCustomInfo['items'];

        $aTmplVarsUsers = array();
        if(!empty($aConnectedIds) && is_array($aConnectedIds)) {
            $aCheckbox = $aInput;
            $aCheckbox['type'] = 'checkbox';
            $aCheckbox['name'] .= '[]';

            foreach($aConnectedIds as $iConnectedId) {
                $oProfile = BxDolProfile::getInstanceMagic($iConnectedId);
                if(!$oProfile)
                    continue;

                $aCheckbox['value'] = $iConnectedId;
                $aCheckbox['checked'] = in_array($iConnectedId, $aSelected) ? 1 : 0;

                $aTmplVarsUsers[] = array(
                    'checkbox' => $this->genInput($aCheckbox),
                    'unit' => $oProfile->getUnit()
                );
            }
        }      

        if(empty($aTmplVarsUsers))
            $aTmplVarsUsers = MsgBox(_t('_Empty'));

        return $this->oTemplate->parseHtmlByName('privacy_users_select_list.html', array(
            'bx_repeat:users' => $aTmplVarsUsers
        ));
    }

    protected function initData($aData)
    {
        if(!empty($aData['profile_id']))
            $this->_iProfileId = (int)$aData['profile_id'];

        if(!empty($aData['content_id']))
            $this->_iContentId = (int)$aData['content_id'];

        if(!empty($aData['object'])) {
            $this->_sObject = $aData['object'];
            $this->_oObject = BxDolPrivacy::getObjectInstance($this->_sObject);
        }

        if(!empty($aData['group_id'])) {
            $this->_iGroupId = (int)$aData['group_id'];
            $this->_aGroupSettings = $this->_oObject ? $this->_oObject->getGroupSettings($this->_iGroupId) : array();
        }

        if(!empty($this->_iProfileId) && !empty($this->_sObject) && !empty($this->_iGroupId)) {
            $this->_aGroupCustomInfo = $this->_oObject->getGroupCustom(array(
                'type' => 'pcog_ext', 
                'profile_id' => $this->_iProfileId,
                'content_id' => $this->_iContentId,
                'object' => $this->_sObject,
                'group_id' => $this->_iGroupId,
                'group_items_table' => $this->_aGroupSettings['db_table_items'],
                'group_items_field' => $this->_aGroupSettings['db_field_item']
            ));

            if(!empty($this->_aGroupCustomInfo) && is_array($this->_aGroupCustomInfo))
                $this->_iGroupCustomId = (int)$this->_aGroupCustomInfo['id'];
        }
    }
}

/** @} */
