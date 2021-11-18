<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioFormsGroupsRoles extends BxTemplStudioGrid
{
    protected $sModule;
    protected $sRolesDataList;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioFormsQuery();

        $sModule = bx_get('module');
        if(!empty($sModule)) {
            $oModule = BxDolModule::getInstance($sModule);
            if ($oModule) {
                $sRolesDataList = isset($oModule->_oConfig->CNF['OBJECT_PRE_LIST_ROLES']) ? $oModule->_oConfig->CNF['OBJECT_PRE_LIST_ROLES'] : '';
                if ($sRolesDataList) {
                    $this->sModule = bx_process_input($sModule);
                    $this->sRolesDataList = $sRolesDataList;
                    $this->_aQueryAppend['module'] = $this->sModule;
                }
            }
        }
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if($this->sRolesDataList != '') {
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `Key` = ?", $this->sRolesDataList);
            return parent::_getDataSql('', $sOrderField, $sOrderDir, $iStart, $iPerPage);
        }

        return false;
    }
}

/** @} */
