<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModTextGridCommon extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sFieldStatus = $CNF['FIELD_STATUS'];

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$CNF = $this->_oModule->_oConfig->CNF;

		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `author`=?", bx_get_logged_profile_id());
		if(!empty($CNF['FIELD_STATUS_ADMIN']))
			$this->_aOptions['source'] .= " AND `" . $CNF['FIELD_STATUS_ADMIN'] . "`='active'";

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
