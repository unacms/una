<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsGridAdministration extends BxBaseModProfileGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getActionAuditContext($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!getParam('sys_audit_enable') || getParam('sys_audit_acl_levels') == '')
            return;
        
        $iProfileId = bx_get_logged_profile_id();
        if (!BxDolAcl::getInstance()->isMemberLevelInSet(explode(',', getParam('sys_audit_acl_levels')), $iProfileId))
            return;
    	
    	$CNF = &$this->_oModule->_oConfig->CNF;
        $oProfile = $this->_getProfileObject($aRow[$CNF['FIELD_ID']]);
        $sUrl = BX_DOL_URL_ROOT . 'page/audit-administration?context_id=' . $oProfile->id();

    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_audit');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getCellName($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = $this->_getProfileObject($aRow['id']);

        return parent::_getCellDefault($oProfile->getUnit(0, array('template' => 'unit_wo_cover')), $sKey, $aField, $aRow);
    }
}

/** @} */
