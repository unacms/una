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

require_once('BxTimelineGridManageTools.php');

class BxTimelineGridAdministration extends BxTimelineGridManageTools
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_timeline';
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sStatusField = $CNF['FIELD_STATUS_ADMIN'];
        $this->_aStatusValues = array('active', 'hidden', 'pending');
    }

    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_timeline_txt_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellOwnerId($mixedValue, $sKey, $aField, $aRow)
    {
        $iProfile = $this->_oModule->_oConfig->isSystem($aRow['type'], $aRow['action']) ? $aRow['owner_id'] : $aRow['object_id'];

        if($this->_bIsApi)
            return ['type' => 'profile', 'data' => BxDolProfile::getData($iProfile)];

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
