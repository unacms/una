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

/*
 * Groups module representation.
 */
class BxBaseModGroupsTemplate extends BxBaseModProfileTemplate
{
    protected $_iUnitCharsSummary;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_bLetterAvatar = false;
        $this->_iUnitCharsSummary = 50;

        $this->_sUnitClassWithCover .= ' bx-base-groups-unit-with-cover';
        $this->_sUnitClass = $this->_sUnitClassWithCover;
        $this->_sUnitClassWoInfo .= ' bx-base-groups-unit-wo-info'; 
        $this->_sUnitClassWoInfoShowCase .= ' bx-base-groups-unit-wo-info bx-base-groups-unit-wo-info-showcase';
        $this->_sUnitClassShowCase .= ' bx-base-groups-unit-with-cover bx-base-groups-unit-showcase';
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);
        
        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        $aVars['title'] = (boolean)$aVars['public'] ? bx_process_output($aData[$CNF['FIELD_NAME']]) : _t($CNF['T']['txt_private_group']);
        $aVars['description'] = '';
        if(!empty($CNF['FIELD_TEXT']) && !empty($aData[$CNF['FIELD_TEXT']]) && (boolean)$aVars['public'])
        	$aVars['description'] = strmaxtextlen(strip_tags($aData[$CNF['FIELD_TEXT']]), $this->_iUnitCharsSummary);

        $aVars['author'] = $oProfile->getDisplayName();
        $aVars['author_url'] = $oProfile->getUrl();
        $aVars['author_icon'] = $oProfile->getIcon();
        $aVars['author_thumb'] = $oProfile->getThumb();
        $aVars['author_avatar'] = $oProfile->getAvatar();

        return $aVars;
    }
}

/** @} */
