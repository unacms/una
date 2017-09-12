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

class BxBaseModGroupsUploaderPictureCrop extends BxBaseModProfileUploaderPictureCrop
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);       
    }

    protected function isAdmin ($iContentId = 0)
    {
        if (parent::isAdmin($iContentId))
            return true;
        if (!$iContentId || !($aDataEntry = $this->_oModule->_oDb->getContentInfoById((int)$iContentId)))
            return false;
        return CHECK_ACTION_RESULT_ALLOWED == $this->_oModule->checkAllowedEdit ($aDataEntry);
    }
}

/** @} */
