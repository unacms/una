<?php defined('BX_DOL') or die('hack attempt');
/**
* Copyright (c) UNA, Inc - https://una.io
* MIT License - https://opensource.org/licenses/MIT
*
* @defgroup    UnaCore UNA Core
* @{
*/

define('BX_DOL_MANAGE_TOOLS_ADMINISTRATION', 'administration');

class BxDolCmtsGridAdministration extends BxTemplGrid
{
    protected $_sManageType;
	protected $_sParamsDivider;
    
    protected $_sFilter1Name;
	protected $_sFilter1Value;
	protected $_aFilter1Values;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sFilter1Name = 'filter1';

        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        $this->_aFilter1Values[''] = _t('_adm_txt_select_module');
        foreach($aModules as $aModule){
            $oModule = BxDolModule::getInstance($aModule['name']);
            if(isset($oModule->_oConfig->CNF['OBJECT_COMMENTS'])){
                $this->_aFilter1Values[$aModule['name']] = $aModule['title'];
            }
        }

    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend[$this->_sFilter1Name] = $this->_sFilter1Value;
        }
       
    	if(!$oTemplate)
			$oTemplate = BxDolTemplate::getInstance();
        parent::__construct ($aOptions, $oTemplate);

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_ADMINISTRATION;
        $this->_sParamsDivider = '#-#';

        $this->_sDefaultSortingOrder = 'DESC';
    }
    
	protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value)){
            $oSelectedModule = BxDolModule::getInstance($this->_sFilter1Value);
            $oCmts = BxDolCmts::getObjectInstance($oSelectedModule->_oConfig->CNF['OBJECT_COMMENTS'], 0, false);
            $this->_aOptions['source'] = "SELECT  sys_cmts_ids.id, sys_cmts_ids.cmt_id, sys_cmts_ids.reports, cmts.*, sys_accounts.email FROM sys_cmts_ids INNER JOIN " . $oCmts->getCommentsTableName() . " cmts ON cmts.cmt_id = sys_cmts_ids.cmt_id INNER JOIN sys_profiles ON cmts.cmt_author_id=sys_profiles.id INNER JOIN sys_accounts ON sys_profiles.account_id=sys_accounts.id WHERE sys_cmts_ids.system_id = " . $oCmts->getSystemId();
        }
        else{
            return '';
        }

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
