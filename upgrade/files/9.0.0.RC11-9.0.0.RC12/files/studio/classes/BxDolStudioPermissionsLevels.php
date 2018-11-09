<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_PERMISSIONS_LEVEL_ID_INT_MAX', round(log(BX_DOL_INT_MAX, 2)));

class BxDolStudioPermissionsLevels extends BxTemplStudioGrid
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioPermissionsQuery();
    }

    protected function delete($iId)
    {
        $aLevel = array();
        $iLevel = $this->oDb->getLevels(array('type' => 'by_id', 'value' => (int)$iId), $aLevel);
        if($iLevel != 1 || empty($aLevel))
            return false;

        if($aLevel['removable'] != 'yes' || $this->oDb->isLevelUsed($aLevel['id']))
            return false;

        // create system event before deletion
        $isStopDeletion = false;
        bx_alert('acl', 'before_delete', $aLevel['id'], 0, array('level' => $aLevel, 'stop_deletion' => &$isStopDeletion));
        if($isStopDeletion)
            return false;

        if(is_numeric($aLevel['icon'])) {
            if(!BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$aLevel['icon'], 0))
                return false;
        }

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $oLanguage->deleteLanguageString($aLevel['name']);
        $oLanguage->deleteLanguageString($aLevel['description']);

        $bResult = $this->oDb->deleteLevel(array('type' => 'by_id', 'value' => $aLevel['id']));
        if($bResult) {
            // create system event
            bx_alert('acl', 'deleted', $aLevel['id'], 0, array('level' => $aLevel));
        }

        return $bResult;
    }

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'yes' : 'no';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'yes' == $mixedState ? true : false;
    }
}

/** @} */
