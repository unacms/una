<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/edit entry form
 */
class BxBaseModFilesFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (isset($CNF['FIELD_PHOTO']) && isset($this->aInputs[$CNF['FIELD_PHOTO']])) {
            $this->aInputs[$CNF['FIELD_PHOTO']]['upload_buttons_titles'] = _t($CNF['T']['form_entry_upload_single_for_update']);
            $this->aInputs[$CNF['FIELD_PHOTO']]['multiple'] = false;
        }
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
}

/** @} */
