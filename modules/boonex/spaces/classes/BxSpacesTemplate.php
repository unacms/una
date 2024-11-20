<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/*
 * Spaces module representation.
 */
class BxSpacesTemplate extends BxBaseModGroupsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct($oConfig, $oDb);
    }
    
    public function entryChilds($aData, $aParams = [])
    {
        $aChild = $this->_oModule->_oDb->getChildEntriesIdByProfileId($aData['profile_id']);
        if(count($aChild) == 0)
            return false;

        if(!isset($aParams['template']))
            $aParams['template'] = 'unit_wo_cover';

        return $this->parseHtmlByName('entry-childs.html', [
            'content' => $this->getBrowseQuick($aChild, $aParams['template'])
        ]);
    }

    public function entryParent($aData, $aParams = [])
    {
        $CNF = $this->_oConfig->CNF;

        $iParentPid = (int)$aData[$CNF['FIELD_PARENT']];
        if($iParentPid == 0)
            return false;
        
        $aParent = $this->_oDb->getContentInfoByProfileId($iParentPid);
        if(empty($aParent) || !is_array($aParent) || $aParent[$CNF['FIELD_STATUS']] != 'active' || $aParent[$CNF['FIELD_STATUS_ADMIN']] != 'active')
            return false;

        if(!isset($aParams['template']))
            $aParams['template'] = 'unit_wo_cover';
        return $this->parseHtmlByName('entry-parent.html', [
            'content' => $this->getBrowseQuick([$aData[$CNF['FIELD_PARENT']]], $aParams['template'])
        ]);
    }
    
    private function getBrowseQuick($aProfiles, $sTemplate = 'unit_wo_cover')
    {
        $sRv = '';
        foreach ($aProfiles as $iProfileId) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if(!$oProfile)
                continue;
            $sRv .= $oProfile->getUnit(false, array('template' => $sTemplate));
        }
        return $sRv;
    }
}

/** @} */
