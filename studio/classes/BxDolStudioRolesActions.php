<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioRolesActions extends BxTemplStudioGrid
{
    protected $iRole = 0;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = BxDolStudioRolesQuery::getInstance();

        $iLevel = (int)bx_get('role');
        if($iLevel > 0)
            $this->iRole = $iLevel;

        $this->_aQueryAppend['role'] = $this->iRole;
    }

    protected function _isRowDisabled($aRow)
    {
        return $aRow['active'] == 0;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->iRole))
            return array();

        $aActions = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
        $aActionsActive = $this->oDb->getActions(array('type' => 'by_role_id_key_id', 'role_id' => $this->iRole));

        foreach($aActions as $iKey => $aAction)
            $aActions[$iKey]['active'] = array_key_exists($aAction['id'], $aActionsActive) ? 1 : 0;

        return $aActions;
    }
}

/** @} */
