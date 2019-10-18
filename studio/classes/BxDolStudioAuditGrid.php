<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAuditGrid extends BxTemplStudioGrid
{
    protected $sModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        
        $this->_sDefaultSortingOrder = 'DESC';
        
        $this->oDb = new BxDolStudioFormsQuery();

        $sModule = bx_get('module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $sModule = $this->sModule;
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if($sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `content_module`=?", $sModule);
        
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
