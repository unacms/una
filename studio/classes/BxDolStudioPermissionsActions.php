<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioPermissionsActions extends BxTemplStudioGrid
{
    protected $iLevel;
    protected $sModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioPermissionsQuery();

        $this->iLevel = 0;
        if(($iLevel = bx_get('level')) !== false) {
            $this->iLevel = (int)$iLevel;
            $this->_aQueryAppend['level'] = $this->iLevel;
        }

        $this->sModule = '';
        if(($sModule = bx_get('module')) !== false) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        if(($sFilter = bx_get('filter')) !== false)
            $this->_processFilter($sFilter);
    }

    protected function _processFilter($sFilter)
    {
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($this->sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);
        
        return $sFilter;
    }

    protected function _isRowDisabled($aRow)
    {
        return $aRow['Active'] == 0;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->iLevel))
            return [];

        $sFilter = $this->_processFilter($sFilter);

        if($this->sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `Module`=?", $this->sModule);

        $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND (`DisabledForLevels`='0' OR `DisabledForLevels`&?=0)", pow(2, ($this->iLevel - 1)));
        $aActions = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        $aActionsActive = [];
        $iActionsActive = $this->oDb->getActions(array('type' => 'by_level_id_key_id', 'value' => $this->iLevel), $aActionsActive);

        foreach($aActions as $iKey => $aAction)
            $aActions[$iKey]['Active'] = array_key_exists($aAction['ID'], $aActionsActive) ? 1 : 0;

        return $aActions;
    }
}

/** @} */
