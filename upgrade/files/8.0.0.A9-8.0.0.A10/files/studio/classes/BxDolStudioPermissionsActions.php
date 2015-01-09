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

class BxDolStudioPermissionsActions extends BxTemplStudioGrid
{
    protected $iLevel = 0;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioPermissionsQuery();

        $iLevel = (int)bx_get('level');
        if($iLevel > 0)
            $this->iLevel = $iLevel;

        $this->_aQueryAppend['level'] = $this->iLevel;
    }

    protected function _isRowDisabled($aRow)
    {
        return $aRow['Active'] == 0;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->iLevel))
            return array();

        $sModule = '';
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if($sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepare(" AND `Module`=?", $sModule);

        $this->_aOptions['source'] .= $this->oDb->prepare(" AND (`DisabledForLevels`='0' OR `DisabledForLevels`&?=0)", pow(2, ($this->iLevel - 1)));
        $aActions = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        $aActionsActive = array();
        $iActionsActive = $this->oDb->getActions(array('type' => 'by_level_id_key_id', 'value' => $this->iLevel), $aActionsActive);

        foreach($aActions as $iKey => $aAction)
            $aActions[$iKey]['Active'] = array_key_exists($aAction['ID'], $aActionsActive) ? 1 : 0;

        return $aActions;
    }
}

/** @} */
