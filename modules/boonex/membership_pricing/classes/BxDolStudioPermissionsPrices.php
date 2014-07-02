<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioGrid');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioPermissionsQuery');

class BxDolStudioPermissionsPrices extends BxTemplStudioGrid
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

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->oDb->prepare("AND `IDLEvel`=? ", $this->iLevel);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);;
    }
}
/** @} */
