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
    protected $_oCmts;

    protected $_sManageType;
    protected $_sParamsDivider;

    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oCmts = null;

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = ['' => _t('_adm_txt_select_module')];

        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule) {
            $oModule = BxDolModule::getInstance($aModule['name']);
            if(!$oModule || !isset($oModule->_oConfig->CNF['OBJECT_COMMENTS']))
                continue;

            $this->_aFilter1Values[$aModule['name']] = $aModule['title'];
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

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'hidden';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(empty($this->_sFilter1Value)) 
            return '';

        $oModule = BxDolModule::getInstance($this->_sFilter1Value);
        if(!$oModule)
            return '';

        $this->_oCmts = BxDolCmts::getObjectInstance($oModule->_oConfig->CNF['OBJECT_COMMENTS'], 0, false);
        if(!$this->_oCmts || !$this->_oCmts->isEnabled())
            return '';

        $this->_aOptions['source'] = $oModule->_oDb->prepareAsString("SELECT 
                `sys_cmts_ids`.`id`, `sys_cmts_ids`.`cmt_id`, `sys_cmts_ids`.`reports`, `sys_cmts_ids`.`status_admin`, `cmts`.*, `sys_accounts`.`email` 
            FROM `sys_cmts_ids` 
            INNER JOIN " . $this->_oCmts->getCommentsTableName() . " AS `cmts` ON `cmts`.`cmt_id`=`sys_cmts_ids`.`cmt_id` 
            LEFT JOIN `sys_profiles` ON `cmts`.`cmt_author_id`=`sys_profiles`.`id` 
            LEFT JOIN `sys_accounts` ON `sys_profiles`.`account_id`=`sys_accounts`.`id` 
            WHERE `sys_cmts_ids`.`system_id`=?", $this->_oCmts->getSystemId());

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
