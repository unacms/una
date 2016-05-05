<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioNavigationImport extends BxTemplStudioGridNavigation
{
    protected $sSet;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioNavigationQuery();

        $sSet = bx_get('set');
        if(!empty($sSet))
            $this->sSet = bx_process_input($sSet);

        $this->_aQueryAppend['set'] = $this->sSet;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $sSet = $sModule = '';
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($sSet, $sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if($sSet != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `set_name`=?", $sSet);

        if($sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `module`=?", $sModule);

        $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `set_name`<>?", $this->sSet);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
