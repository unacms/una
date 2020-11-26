<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_ROLES_ROLE_ID_INT_MAX', round(log(BX_DOL_INT_MAX, 2)));

class BxDolStudioRolesLevels extends BxTemplStudioGrid
{
    protected $aNonDeletable;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = BxDolStudioRolesQuery::getInstance();

        $this->aNonDeletable = array(
            BX_DOL_STUDIO_ROLE_MASTER, 
            BX_DOL_STUDIO_ROLE_OPERATOR
        );
    }

    protected function _delete($mixedId)
    {
        $iId = (int)$mixedId;
        $aRole = $this->oDb->getRoles(array('type' => 'by_id', 'id' => $iId));
        if(empty($aRole) || !is_array($aRole))
            return false;

        if(in_array($iId, $this->aNonDeletable) || $this->oDb->isRoleUsed($iId))
            return false;

        $isStopDeletion = false;
        bx_alert('roles', 'before_delete', $iId, 0, array('role' => $aRole, 'stop_deletion' => &$isStopDeletion));
        if($isStopDeletion)
            return false;

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $oLanguage->deleteLanguageString($aRole['title']);
        $oLanguage->deleteLanguageString($aRole['description']);

        if(!parent::_delete($mixedId)) 
            return false;

        bx_alert('roles', 'deleted', $iId, 0, array('role' => $aRole));

        return true;
    }
}

/** @} */
