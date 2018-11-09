<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLog extends BxDolFactory implements iBxDolSingleton
{
    protected $_sFile;
    protected $_sName;

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
    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLog();

        $GLOBALS['bxDolClasses'][__CLASS__]->reset();
        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Reset custom log file and log name 
     * to be empty to ready for future usage.
     */
    public function reset()
    {
        $this->_sFile = '';
        $this->_sName = '';
    }

    /**
     * Set custom log file. The file should have necessary permissions.
     * @param string $sFile - full path to custom log file.
     */
    public function setFile($sFile)
    {
        $this->_sFile = $sFile;
    }

    /**
     * Set log name. Log file will be created\updated in standard log directory.
     * @param string $sName - log file name without path and extension.
     */
    public function setName($sName)
    {
        $this->_sName =  $sName;
    }

    /**
     * Writes content to a log file. Can get any number of arguments to be written 
     * in one write session. Each argument can be a string, an array or an object.
     */
    public function write()
    {
        if(func_num_args() == 0)
            return;

        try {
            $sFile = '';

            if(!empty($this->_sFile))
                $sFile = $this->_sFile;
            else 
                $sFile = BX_DIRECTORY_PATH_LOGS . '/' . $this->_sName . '.log';

            if(empty($sFile))
                return;

            file_put_contents($sFile, date('m-d H:i:s') . ":\n", FILE_APPEND);

            $aArgs = func_get_args();
            foreach($aArgs as $mixedArg) {
                if(is_array($mixedArg))
                    $mixedArg = var_export($mixedArg, true);	
                else if(is_object($mixedArg))
                    $mixedArg = json_encode($mixedArg);

                file_put_contents($sFile, $mixedArg . "\n", FILE_APPEND);
            }
        }
        catch (Exception $oException) {
            echo 'Error: ' . $oException->getMessage();
        }
    }
}

/** @} */
