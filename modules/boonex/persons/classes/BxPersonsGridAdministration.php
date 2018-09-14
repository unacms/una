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
    protected $_sConfirmationType;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_persons';
        parent::__construct ($aOptions, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        $this->_sFilter2Name = 'filter2';
        $aTmp = array();
        BxDolAclQuery::getInstance()->getLevels(array('type' => 'all_active_not_automatic_pair'), $aTmp);
        foreach ($aTmp as $sKey => $sValue) {
            $this->_aFilter2Values["level" . $sKey] = $sValue;
        }
        
        $sFilter2 = bx_get($this->_sFilter2Name);
        if(!empty($sFilter2)) {
            $this->_sFilter2Value = bx_process_input($sFilter2);
            $this->_aQueryAppend[$this->_sFilter2Name] = $this->_sFilter2Value;
        }
        $this->_sConfirmationType = getParam('sys_account_confirmation_type');
        if ($this->_sConfirmationType != BX_ACCOUNT_CONFIRMATION_NONE)
            $this->_aFilter1Values['unconfirmed'] = $CNF['T']['filter_item_unconfirmed'];
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value)){
            if ($this->_sFilter1Value == 'unconfirmed'){
                switch ($this->_sConfirmationType) {
                    case BX_ACCOUNT_CONFIRMATION_EMAIL:
                        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `ta`.`email_confirmed` = 0 ");
                        break;
                    case BX_ACCOUNT_CONFIRMATION_PHONE:
                        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `ta`.`phone_confirmed` = 0 ");
                        break;
                    case BX_ACCOUNT_CONFIRMATION_EMAIL_PHONE:
                        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND (`ta`.`email_confirmed` = 0 AND `ta`.`phone_confirmed` = 0) ");
                        break;
                }
            }
            else{
        	    $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`status`=?", $this->_sFilter1Value);
            }
        }
        if(!empty($this->_sFilter2Value)){
			$iLevel = intval(str_replace("level", "", $this->_sFilter2Value));
			if ($iLevel <> 3)
        		$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`id` IN (SELECT `IDMember` FROM `sys_acl_levels_members` WHERE IDLevel = ?) ", $iLevel);
			else
				$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`id` NOT IN (SELECT `IDMember` FROM `sys_acl_levels_members`) ");
		}

        return parent::_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values) . $this->_getSearchInput();
    }
    
}

/** @} */
