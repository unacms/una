<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for Cron
 * @see BxDolCron
 */
class BxDolCronQuery extends BxDolDb implements iBxDolSingleton
{
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolCronQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getJobs()
    {
    	return $this->fromCache('sys_cron_jobs', 'getAll', "SELECT * FROM `sys_cron_jobs`");
    }

    public function updateJob($iId, $aFields = array())
    {
    	$sQuery = $this->prepare("UPDATE `sys_cron_jobs` SET " . $this->arrayToSQL($aFields) . " WHERE `id` = ?", $iId);
        return $this->query($sQuery);
    }

    public function addTransientJobClass($sName, $sClass, $sFile)
    {
    	$sQuery = $this->prepare("INSERT INTO `sys_cron_jobs` SET `name`=?, `time`='transient', `class`=?, `file`=?", $sName, $sClass, $sFile);
        return (int)$this->query($sQuery) > 0;
    }

	public function addTransientJobService($sName, $mixedService)
    {
    	if(is_array($mixedService))
    		$mixedService = call_user_func_array('BxDolService::getSerializedService', $mixedService);

    	if(!BxDolService::isSerializedService($mixedService))
    		return false;

    	$sQuery = $this->prepare("INSERT INTO `sys_cron_jobs` SET `name`=?, `time`='transient', `service_call`=?", $sName, $mixedService);
        return (int)$this->query($sQuery) > 0;
    }

    public function getTransientJobs()
    {
    	return $this->getAllWithKey("SELECT * FROM `sys_cron_jobs` WHERE `time`='transient'", 'name');
    }

    public function deleteTransientJobs()
    {
    	return $this->query("DELETE FROM `sys_cron_jobs` WHERE `time`='transient'");
    }
}

/** @} */
