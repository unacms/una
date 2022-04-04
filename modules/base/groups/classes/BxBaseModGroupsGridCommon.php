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

class BxBaseModGroupsGridCommon extends BxBaseModGroupsGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sStatusField = $CNF['FIELD_STATUS'];
        $this->_aStatusValues = [
            BX_BASE_MOD_GENERAL_STATUS_ACTIVE, 
            BX_BASE_MOD_GENERAL_STATUS_HIDDEN
        ];

        if($this->_oModule->_oConfig->isAutoApprove() && isset($this->_aFilter1Values[BX_BASE_MOD_GENERAL_STATUS_PENDING]))
            unset($this->_aFilter1Values[BX_BASE_MOD_GENERAL_STATUS_PENDING]);

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`account_id`=?", getLoggedId());

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
