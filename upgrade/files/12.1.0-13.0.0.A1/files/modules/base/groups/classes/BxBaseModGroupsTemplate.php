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

    protected $_sUnitClassWoCover;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->_bLetterAvatar = false;
        $this->_iUnitCharsSummary = 50;

        $this->_sUnitClassWoCover = $this->_sUnitClass; //--- Save default 'Unit' class (from BxBaseModProfileTemplate) as 'Unit W\O Cover' class here.
        $this->_sUnitClassWithCover .= ' bx-base-groups-unit-with-cover';
        $this->_sUnitClass = $this->_sUnitClassWithCover;
        $this->_sUnitClassWoInfo .= ' bx-base-groups-unit-wo-info'; 
        $this->_sUnitClassWoInfoShowCase .= ' bx-base-groups-unit-wo-info bx-base-groups-unit-wo-info-showcase';
        $this->_sUnitClassShowCase .= ' bx-base-groups-unit-with-cover bx-base-groups-unit-showcase';
    }

    public function addLocationBase()
    {
        parent::addLocationBase();

        $this->addLocation('mod_groups', BX_DIRECTORY_PATH_MODULES . 'base' . DIRECTORY_SEPARATOR . 'groups' . DIRECTORY_SEPARATOR, BX_DOL_URL_MODULES . 'base/groups/');
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $mixedTemplate = false, $aParams = array())
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $mixedTemplate, $aParams);

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

    public function getPopupSetRole($aRoles, $iProfileId, $iProfileRole)
    {
        $sJsObject = $this->_oConfig->getJsObject('main');
        $sHtmlIdPrefix = str_replace('_', '-', $this->_oConfig->getName()) . '-role';

        $aTmplVarsRoles = array();
        foreach($aRoles as $iRole => $sRole)
            $aTmplVarsRoles[] = array(
                'id' => $sHtmlIdPrefix . '-' . $iRole, 
                'value' => $iRole,
                'onclick' => $sJsObject . '.onClickSetRoleMulti(this, ' . $iProfileId . ', ' . $iRole . ')',
                'title' => $sRole, 
                'bx_if:show_checked' => array(
                    'condition' => $iRole != 0 && $iProfileRole & (1 << ($iRole - 1)),
                    'content' => array()
                )
            );

        return $this->parseHtmlByName('set_role_popup.html', array(
            'bx_repeat:roles' => $aTmplVarsRoles
        ));
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';
        
        switch($sTemplateName) {
            case 'unit_wo_cover.html':
                $sResult = $this->_sUnitClassWoCover;
                break;

            default:
                $sResult = parent::_getUnitClass($aData, $sTemplateName);
        }

        return $sResult;
    }

    protected function _getUnitSize($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            case 'unit.html':
            case 'unit_with_cover.html':
                $sResult = 'ava';
                break;

            default:
                $sResult = $this->_sUnitSizeDefault;
                break;
        }

        return $sResult;
    }
}

/** @} */
