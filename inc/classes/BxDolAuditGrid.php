<?php defined('BX_DOL') or die('hack attempt');
/**
* Copyright (c) UNA, Inc - https://una.io
* MIT License - https://opensource.org/licenses/MIT
*
* @defgroup    UnaCore UNA Core
* @{
*/

define('BX_DOL_MANAGE_TOOLS_ADMINISTRATION', 'administration');

class BxDolAuditGrid extends BxTemplGrid
{
    protected $_sParamsDivider;
    
    protected $_sFilter1Name;
	protected $_sFilter1Value;
	protected $_aFilter1Values;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sFilter1Name = 'module';

        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules'));
        $this->_aFilter1Values[''] = _t('_adm_txt_select_module');
        foreach($aModules as $aModule){
            $this->_aFilter1Values[$aModule['name']] = $aModule['title'];
        }

    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend[$this->_sFilter1Name] = $this->_sFilter1Value;
        }
        
        parent::__construct ($aOptions, $oTemplate);

        $this->_sParamsDivider = '#-#';
        $this->_sDefaultSortingOrder = 'DESC';
        
        $this->oDb = new BxDolStudioFormsQuery();
    }
    
	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

        if($this->_sFilter1Value != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `content_module` = ?", $this->_sFilter1Value);
        if(bx_get('content_id') && is_numeric(bx_get('content_id')))
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `content_id` = ?", (int)bx_get('content_id'));
        if(bx_get('actor_id')  && is_numeric(bx_get('actor_id')))
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `profile_id` = ?", (int)bx_get('actor_id'));
        if(bx_get('context_id')  && is_numeric(bx_get('context_id')))
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `context_profile_id` = ?", (int)bx_get('context_id'));
        
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
