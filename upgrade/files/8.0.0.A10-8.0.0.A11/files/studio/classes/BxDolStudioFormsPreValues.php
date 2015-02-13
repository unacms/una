<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_FIELD_PRE_VALUE_INT_MAX', round(log(BX_DOL_INT_MAX, 2)));

class BxDolStudioFormsPreValues extends BxTemplStudioGrid
{
    protected $sModule;
    protected $sList;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioFormsQuery();

        $sModule = bx_get('module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sList = bx_get('list');
        if(!empty($sList)) {
            $this->sList = bx_process_input($sList);
            $this->_aQueryAppend['list'] = $this->sList;
        }
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->sList))
            return array();

        $this->_aOptions['source'] .= $this->oDb->prepare(" AND `Key`=? ", $this->sList);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
