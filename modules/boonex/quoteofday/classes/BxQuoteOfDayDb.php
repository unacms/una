<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    QuoteOfTheDay Quote of the Day
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

class BxQuoteOfDayDb extends BxBaseModGeneralDb
{
	function __construct(&$oConfig) 
    {
		parent::__construct($oConfig);	
    }
	
	public function getHiddenItemsCount()
	{
		$CNF = &$this->_oConfig->CNF;
		$sSql = $this->prepare("SELECT COUNT(*) FROM " . $CNF['TABLE_ENTRIES']. " WHERE " . $CNF['FIELD_STATUS']. "!='active'");
		
        return $this->getOne($sSql);
	}
	
	public function getData()
	{
		$CNF = &$this->_oConfig->CNF;
		return  $this->getColumn("SELECT `" . $CNF['FIELD_TEXT'] . "` FROM " . $CNF['TABLE_ENTRIES'] . " WHERE " . $CNF['FIELD_STATUS'] . "='active'");
	}
	
}

/** @} */
