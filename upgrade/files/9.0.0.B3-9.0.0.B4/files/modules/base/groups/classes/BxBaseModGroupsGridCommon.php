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

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`account_id`=?", getLoggedId());

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
