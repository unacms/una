<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Social Engine Migration
 * @ingroup     UnaModules
 *
 * @{
 */
 
define('BX_SEMIG_SUCCESSFUL', 1);
define('BX_SEMIG_FAILED', 0);
 
class BxSEMigData
{      
	protected $_oMainModule;
	protected $_seDb;
	protected $_sPrefix;
	protected $_oDb;
	protected $_sEnginePrefix = 'engine4_';
	protected $_iTransferred = 0;

	public function BxSEMigData(&$oMainModule, &$seDb)
	{
	     $this -> _sPrefix = $oMainModule -> _aModule['db_prefix'];
	     $this -> _oMainModule = $oMainModule;
	     $this -> _seDb  = $seDb;
	     $this -> _oDb = $this -> _oMainModule -> _oDb;
		 $this -> _sEnginePrefix = $this -> _oMainModule -> _oConfig -> getEngineVersionPrefix();
	}



	/**
	* Performs Migration Data
	* @return boolean
	*/
	public function runMigration(){
	    $this -> setResultStatus(_t('_bx_se_migration_define_migration_method'));
	    return BX_SEMIG_FAILED;
	}

	/**
	* Gets total records for transferring
	* @return boolean
	*/
	public function getTotalRecords(){
	    $this -> setResultStatus(_t('_bx_se_migration_define_total_method'));
	    return BX_SEMIG_FAILED;		
	}

	/**
	* Set Migration Status
	* @param string $sStatus message
	*/         
	protected function setResultStatus($sStatus){
	    $sQuery = $this -> _oDb -> prepare("UPDATE `{$this -> _sPrefix}transfers` SET `status_text` = ? WHERE `module` = ? ", $sStatus, $this -> _oMainModule -> sProcessedModule);
	    $this -> _oDb -> query($sQuery);
	}

	/**
	* Returns transferred Una's profile id of transferred Social Engine's member
	* @param int $iSEId social engine's profile ID
	* @return Integer
	*/           
	
	protected function getProfileId($iSEId){
	    return (int)$this -> _oDb -> getOne("SELECT `profile_id` FROM  `sys_accounts` WHERE  `se_id` =  '{$iSEId}' LIMIT 1");       
	}
}
   
/** @} */