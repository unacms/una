<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxFilesFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if (isset($this->aInputs[$CNF['FIELD_PHOTO']]))
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = false;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array()) 
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]) && $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) {

            $oProfile = isset($aValues[$CNF['FIELD_AUTHOR']]) ? BxDolProfile::getInstance($aValues[$CNF['FIELD_AUTHOR']]) : false;
            $oPrivacy->setCustomPrivacy($oProfile && BxDolService::call($oProfile->getModule(), 'is_group_profile') ? true : false);
            
            $aSave = array('db' => array('pass' => 'Xss'));
            array_walk($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']], function ($a, $k, $aSave) {
                if (in_array($k, array('info', 'caption', 'value')))
                    $aSave[0][$k] = $a;
            }, array(&$aSave));
            
            $aGroupChooser = $oPrivacy->getGroupChooserCustom($CNF['OBJECT_PRIVACY_VIEW'], $oPrivacy->isCustomPrivacy());
            
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']] = array_merge($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']], $aGroupChooser, $aSave);
        }
        
        return parent::initChecker ($aValues, $aSpecificValues);
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField);

        $this->_oModule->_oDb->updateFileId($iContentId, $iFileId);
    }
}

/** @} */
