<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolBackgroundJobs  extends BxDolFactory implements iBxDolSingleton
{
    protected $_sObjectLog;
    protected $_oQuery;
    

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
        
        $this->_sObjectLog = 'sys_background_jobs';

        $this->_oQuery = new BxDolBackgroundJobsQuery();
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolBackgroundJobs();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public function add($sName, $mixedServiceCall, $iPriority = 0)
    {
        if(is_array($mixedServiceCall))
            $mixedServiceCall = call_user_func_array(['BxDolService', 'getSerializedService'], $mixedServiceCall);

        if(!$this->_oQuery->addJob($sName, $mixedServiceCall, $iPriority))
            return false;

        bx_log($this->_sObjectLog, "Added: " . $sName);

        return true;
    }

    public function delete($sName)
    {
        if(!$this->_oQuery->deleteJob($sName))
            return false;

        bx_log($this->_sObjectLog, "Deleted: " . $sName);

        return true;
    }

    public function exists($sName)
    {
        $aJob = $this->_oQuery->getJobs([
            'sample' => 'name', 
            'name' => $sName
        ]);

        return !empty($aJob) && is_array($aJob);
    }

    public function process($mixedJob)
    {
        if(!empty($mixedJob) && !is_array($mixedJob))
            $mixedJob = $this->_oQuery->getJobs(['sample' => 'name', 'name' => $mixedJob]);

        if(empty($mixedJob) || !is_array($mixedJob))
            return false;

        if(empty($mixedJob['service_call']) || !BxDolService::isSerializedService($mixedJob['service_call']))
            return false;

        BxDolService::callSerialized($mixedJob['service_call']);

        $this->_oQuery->deleteJob($mixedJob['name']);

        bx_log($this->_sObjectLog, "Processed: " . $mixedJob['name']);

        return true;
    }

    public function processAll()
    {
        $aJobs = $this->_oQuery->getJobs(['sample' => 'process', 'with_priority' => true]);
        if(empty($aJobs) || !is_array($aJobs))
            return true;

        $iProcessed = 0;
        foreach($aJobs as $aJob)
            if($this->process($aJob))
                $iProcessed += 1;

        bx_log($this->_sObjectLog, "Processed: all (" . $iProcessed . " from " . count($aJobs) . ")");

        return true;
    }
}

/** @} */
