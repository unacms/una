<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxDonationsGridListAll.php');

class BxDonationsGridList extends BxDonationsGridListAll
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $iProfileId = bx_get_logged_profile_id();
        if($iProfileId !== false)
            $this->_aQueryAppend['profile_id'] = (int)$iProfileId;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_aQueryAppend['profile_id']))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `te`.`profile_id`=?", $this->_aQueryAppend['profile_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
