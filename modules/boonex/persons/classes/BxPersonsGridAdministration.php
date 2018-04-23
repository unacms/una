<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxPersonsGridAdministration extends BxBaseModProfileGridAdministration
{
    protected $_sFilter2Name;
	protected $_sFilter2Value;
	protected $_aFilter2Values;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_persons';
        parent::__construct ($aOptions, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_sFilter2Name = 'filter2';
        $aTmp = array();
        BxDolAclQuery::getInstance()->getLevels(array('type' => 'all_active_pair'), $aTmp);
        foreach ($aTmp as $sKey => $sValue) {
            $this->_aFilter2Values["level" . $sKey] = $sValue;
        }
        
        $sFilter2 = bx_get($this->_sFilter2Name);
        if(!empty($sFilter2)) {
            $this->_sFilter2Value = bx_process_input($sFilter2);
            $this->_aQueryAppend[$this->_sFilter2Name] = $this->_sFilter2Value;
        }
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`status`=?", $this->_sFilter1Value);
        
        if(!empty($this->_sFilter2Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`id` IN (SELECT `IDMember` FROM `sys_acl_levels_members` WHERE IDLevel = ?) ", str_replace("level", "", $this->_sFilter2Value));

        return parent::_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values) . $this->_getSearchInput();
    }
    
}

/** @} */
