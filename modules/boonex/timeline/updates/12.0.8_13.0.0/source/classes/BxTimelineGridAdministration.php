<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxTimelineGridAdministration extends BxBaseModGeneralGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_timeline';
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'hidden';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    public function performActionDelete($aParams = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $aContentInfo = $this->_oModule->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));
            if ($this->_oModule->isAllowedDelete($aRow) === true && $this->_oModule->deleteEvent($aContentInfo)){
                $aIdsAffected[] = $iId;
                $iAffected++;
            }
        }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_delete'])));
    }
    
    protected function _getActionDelete($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($sType == 'single' && $this->_sManageType == BX_DOL_MANAGE_TOOLS_ADMINISTRATION && $this->_oModule->isAllowedDelete($aRow) !== true)
            return '';

    	return $this->_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellDescription($mixedValue, $sKey, $aField, $aRow)
    {
        $iProfile = $this->_oModule->_oConfig->isSystem($aRow['type'], $aRow['action']) ? $aRow['owner_id'] : $aRow['object_id'];
        $oProfile = $this->_getProfileObject($iProfile);

        $mixedValue = $this->_oTemplate->parseHtmlByName('grid_link.html', array(
            'href' => $this->_oModule->serviceGetLink($aRow['id']),
            'title' => bx_replace_markers($aRow['description'], array(
                'profile_name' => $oProfile->getDisplayName()
            ))
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellOwnerId($mixedValue, $sKey, $aField, $aRow)
    {
        $iProfile = $this->_oModule->_oConfig->isSystem($aRow['type'], $aRow['action']) ? $aRow['owner_id'] : $aRow['object_id'];
    	$oProfile = $this->_getProfileObject($iProfile);
    	$sProfile = $oProfile->getDisplayName();

    	$sAccountEmail = '';
    	$sManageAccountUrl = '';
    	if($oProfile && $oProfile instanceof BxDolProfile && BxDolAcl::getInstance()->isMemberLevelInSet(128)) {
            $sAccountEmail = $oProfile->getAccountObject()->getEmail();
            $sManageAccountUrl = $this->_getManageAccountUrl($sAccountEmail);
    	}

        $mixedValue = $this->_oTemplate->parseHtmlByName('author_link.html', array(
            'href' => $oProfile->getUrl(),
            'title' => $sProfile,
            'content' => $sProfile,
            'bx_if:show_account' => array(
                'condition' => !empty($sManageAccountUrl), 
                'content' => array(
                    'href' => $sManageAccountUrl,
                    'title' => _t($this->_oModule->_oConfig->CNF['T']['grid_txt_account_manager']),
                    'content' => $sAccountEmail
                )
            )
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
}

/** @} */
