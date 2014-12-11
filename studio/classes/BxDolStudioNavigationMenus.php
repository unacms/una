<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioGridNavigation');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioNavigationQuery');

class BxDolStudioNavigationMenus extends BxTemplStudioGridNavigation
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioNavigationQuery();
    }
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $sModule = '';
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if($sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepare(" AND `tm`.`module`=?", $sModule);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
