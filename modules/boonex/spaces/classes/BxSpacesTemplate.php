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
    
    public function entryChilds($aData, $aParams = array())
    {
        $CNF = $this->_oConfig->CNF;
        $aChild = $this->_oModule->_oDb->getChildEntriesIdByProfileId($aData[$CNF['FIELD_ID']]);
        
        if (count($aChild) == 0)
            return false;
        
        if (!isset($aParams['template']))
            $aParams['template'] = 'unit_wo_cover';
        
        return $this->parseHtmlByName('entry-childs.html', array('content' => $this->getBrowseQuick($aChild, $aParams['template'])));
    }
    
    public function entryParent($aData, $aParams = array())
    {
        $CNF = $this->_oConfig->CNF;
        if ($aData[$CNF['FIELD_PARENT']] == 0)
            return false;

        if (!isset($aParams['template']))
            $aParams['template'] = 'unit_wo_cover';
        return $this->parseHtmlByName('entry-parent.html', array('content' => $this->getBrowseQuick(array($aData[$CNF['FIELD_PARENT']]), $aParams['template'])));
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
