<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioGrid');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioPermissionsQuery');

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

        if(is_numeric($aLevel['icon'])) {
            bx_import('BxDolStorage');
            if(!BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$aLevel['icon'], 0))
                return false;
        }

        bx_import('BxDolStudioLanguagesUtils');
        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $oLanguage->deleteLanguageString($aLevel['name']);
        $oLanguage->deleteLanguageString($aLevel['description']);

        return $this->oDb->deleteLevel(array('type' => 'by_id', 'value' => $aLevel['id']));
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
