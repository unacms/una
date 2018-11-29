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

bx_import('BxDolDb');

class BxDolMDb extends BxBaseModGeneralDb
{	
	public function __construct(&$oConfig)
	{
		parent::__construct($oConfig);			
	}

	/** 
	* Save Dolphin configuration 
	* @param array $aConfig parameters
    * @return boolean if requests are successful
	*/ 
	public function saveConfig($aConfig)
	{
		if(is_array($aConfig))
		{
            
			$bRecords = true;
			foreach($aConfig as $sName => $mValue)
            {
                $mValue = bx_process_input($mValue);
                $sName  = bx_process_input($sName);
				$sQuery = $this -> prepare("REPLACE INTO `{$this -> _sPrefix}config` SET `name` = ?, `value` = ?", $sName, $mValue);
				if (!$this -> res($sQuery))
					$bRecords &= false;
            }	
		}
		
		return $bRecords;
	}

	/**
     * Removes Dolphin's transfer settings
     * @return boolean if requests are successful
     */
    public function removeConfig()
    {
        $this -> query("TRUNCATE TABLE `bx_dolphin_config`");
        $this -> query("TRUNCATE TABLE `bx_dolphin_transfers`");
    }

    /**
	* Get config parameter value
	* @param string $sConfigName param's name 
	* @return string 
	*/
	public function getExtraParam($sConfigName){ 
		$sQuery = $this -> prepare("SELECT `value` FROM `{$this -> _sPrefix}config` WHERE `name` = ?", $sConfigName);
		return $this -> getOne($sQuery);
	}

	/** 
	* Creates/edits migration list
	* @param array $aConfig Dolphin config info 
	*/	
	public function createConfig($aConfig){
        if( is_array($aConfig) ) {
            foreach($aConfig as $sName => $mValue)
            {
                $mValue = $this -> escape($mValue);
                $sName  = $this -> escape($sName);

                // add new;
                if(!$this -> getExtraParam($sName))
				{
                    $sQuery = $this -> prepare("INSERT INTO `{$this -> _sPrefix}config` SET `name` = ?, `value` = ?", $sName, $mValue);
                    $this -> query($sQuery);
                }
                else
				{
                // update exsisting;
                    $sQuery = $this -> prepare("UPDATE `{$this -> _sPrefix}config` SET  `value` = ? WHERE `name` = ?", $mValue, $sName);					
                    $this -> query($sQuery);
                }
            }
        }
	}
	
	/** 
	* Returns Migration Result information
	* @param string $sModule name
	* @return  string
	*/
	public function getTransferStatusText($sModule)
    {
        $sQuery = $this -> prepare("SELECT `status_text` FROM `{$this -> _sPrefix}transfers` WHERE `module` = ? LIMIT 1", $sModule);
        return $this -> getOne($sQuery);
    }

	/** 
	* Returns current migration status
	* @param string $sModule name @uses  BxDolMConfig::_aMigrationModules
	* @return  string
	*/
	 public function getTransferStatus($sModule)
	 {
	     $sQuery = $this -> prepare("SELECT `status` FROM `{$this -> _sPrefix}transfers` WHERE `module` = ? LIMIT 1", $sModule);
	     return $this -> getOne($sQuery);
	 }

	 public function updateTransferStatus($sModule, $sStatus)
	 {            
	     $this -> query("UPDATE `{$this -> _sPrefix}transfers` SET `status` = :status WHERE `module` = :module", array('module' => $sModule, 'status' => $sStatus));
	 }

	/** 
	* Check if configuration already exists
	* @return  Boolean
	*/
	 public function isConfigInstalled()
	 {
		return $this -> getOne('SELECT COUNT(*) FROM `bx_dolphin_config`');
	 }
	
	/** 
	* Adds module for migration to migration list
	* @param string $sName modules name
	* @param string $mixedNumber records number
	* @return mixed 
	*/		
	public function addToTransferList($sName, $mixedNumber)
	{
		$sNumber = $mixedNumber;
		if (is_array($mixedNumber))
			$sNumber = implode(',', $mixedNumber);	
		return $this -> query("INSERT INTO `{$this -> _sPrefix}transfers` SET `module` = :module, `status` = 'not_started', `number` = :number", array('module' => $sName, 'number' => $sNumber));
	}		

	/** 
	* Check whether required Una plug-in installed for transferring
	* @param string $sName pugin name
	* @return Boolean
	*/			
	public function isPluginInstalled($sName)
	{
		$sQuery = $this -> prepare("SELECT COUNT(*) FROM `sys_modules` WHERE `type` = 'module' AND `name` = ? LIMIT 1" , $sName);
		return (int)$this -> getOne($sQuery) == 1;
	}
	
	public function cleanTransfersTable()
	{
		return $this -> query("TRUNCATE TABLE `{$this -> _sPrefix}transfers`");
	}

	public function isFinished($aElements){
	    if (empty($aElements))
	        return false;

	    $sList = implode("','", $aElements);
	    return $this -> getOne("SELECT COUNT(*) FROM `{$this -> _sPrefix}transfers` WHERE `module` IN (:list) AND `status` != 'finished'", array('list' => $sList)) == 0;
    }

    public function getPrivacyList()
    {
        return $this -> getAll("SELECT
                   `id`,
                   `title`,
                   `check`,
                   `active`
                FROM `sys_privacy_groups`
                WHERE `active`='1' AND `visible`='1'");
    }
}

/** @} */
