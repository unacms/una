<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxAdsGridLicensesAdministration.php');

class BxAdsGridLicenses extends BxAdsGridLicensesAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_aQueryAppend['profile_id']))
            return array();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tl`.`profile_id`=?", $this->_aQueryAppend['profile_id']);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
