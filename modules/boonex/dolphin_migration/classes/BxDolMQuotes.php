<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DolphinMigration  Dolphin Migration
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxDolMData.php');
bx_import('BxDolStorage');
	
class BxDolMQuotes extends BxDolMData
{	
	public function __construct(&$oMigrationModule, &$oDb)
	{
        parent::__construct($oMigrationModule, $oDb);
		$this -> _sModuleName = 'quotes';
		$this -> _sTableWithTransKey = 'bx_quoteofday_internal';
    }    
	
	public function getTotalRecords()
	{
		return $this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "`");
	}
		
	public function runMigration() 
	{        
		if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_quotes'));		
			
		$this -> createMIdField();
		$aQuotes = $this -> _mDb -> getAll("SELECT * FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` ORDER BY `ID`");
		$aParticipantsList = array();
		foreach($aQuotes as $iMes => $aQuote)
		{
			$iQuoteId = $this -> isItemExisted($aQuote['ID']);
			if (!$iQuoteId)
			{
				$sQuery = $this -> _oDb -> prepare( 
						"
							INSERT INTO
								`{$this -> _sTableWithTransKey}`
							SET
								`text`	= ?,
								`added`	= UNIX_TIMESTAMP()
						",
						$aQuote['Text']
					);
			
					$this -> _oDb -> query($sQuery);
					
					$iQuoteId = $this -> _oDb -> lastId();
					if (!$iQuoteId)
					{
						$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_quotes_error', (int)$aQuote['ID']));
						return BX_MIG_FAILED;					
					}
					
					$this -> setMID($iQuoteId, $aQuote['ID']);
				
				$this -> _iTransferred++;
			}		
		}

        // set as finished;
		$this -> setResultStatus(_t('_bx_dolphin_migration_started_migration_quotes_finished', $this -> _iTransferred));
        return BX_MIG_SUCCESSFUL;
    }
	
	public function removeContent()
	{
		if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sTransferFieldIdent))
			return false;
		
		$aRecords = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` !=0 ");	
		$iNumber = 0;		
		if (!empty($aRecords)) {
            foreach ($aRecords as $iKey => $aValue) {
                $this->_oDb->query("DELETE FROM `{$this -> _sTableWithTransKey}` WHERE `id`=:id", array('id' => $aValue['id']));
                $iNumber++;
            }
        }

		parent::removeContent();
		return $iNumber;
	}
}

/** @} */
