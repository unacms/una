<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_AI_AUTOMATOR_EVENT', 'event');
define('BX_DOL_AI_AUTOMATOR_SCHEDULER', 'scheduler');
define('BX_DOL_AI_AUTOMATOR_WEBHOOK', 'webhook');

define('BX_DOL_AI_AUTOMATOR_STATUS_AUTO', 'auto');
define('BX_DOL_AI_AUTOMATOR_STATUS_MANUAL', 'manual');
define('BX_DOL_AI_AUTOMATOR_STATUS_READY', 'ready');

class BxDolAI extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;
    protected $_iProfileId;
    
    protected $_aExcludeAlertUnits;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oDb = new BxDolAIQuery();

        $this->_iProfileId = (int)getParam('sys_profile_bot'); 

        $this->_aExcludeAlertUnits = [
            'system', 'module_template_method_call'
        ];
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
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolAI();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getProfileId()
    {
        return $this->_iProfileId;
    }
    
    public function getDefaultModel()
    {
        return (int)getParam('sys_agents_model');
    }

    public function getModels()
    {
        return $aModel = $this->_oDb->getModelsBy(['sample' => 'all_pairs']);
    }

    public function getModel($iId)
    {
        $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $iId]);
        if(!empty($aModel['params']))
            $aModel['params'] = json_decode($aModel['params'], true);

        return $aModel;
    }

    public function getModelObject($iId)
    {
        if(!$iId)
            $iId = $this->getDefaultModel();
        if(!$iId)
            return false;

        return BxDolAIModel::getObjectInstance($iId);
    }
    
    public function getProviderObject($iId)
    {
        if(!$iId)
            return false;

        return BxDolAIProvider::getObjectInstance($iId);
    }   

    public function getAutomator($iId, $bFullInfo = false)
    {
        $aAutomator = $this->_oDb->getAutomatorsBy(['sample' => 'id' . ($bFullInfo ? '_full' : ''), 'id' => $iId]);
        if(!empty($aAutomator['params']))
            $aAutomator['params'] = json_decode($aAutomator['params'], true);
        if($bFullInfo && !empty($aAutomator['model_params']))
            $aAutomator['model_params'] = json_decode($aAutomator['model_params'], true);

        return $aAutomator;
    }
    
    public function getAutomatorInstruction($sType, $aParams = [])
    {
        $mixedResult = '';

        switch($sType){
            case 'providers':
                $aProviders = $this->_oDb->getProviderBy(['sample' => 'ids', 'ids' => $aParams]);
                if(!empty($aProviders) && is_array($aProviders)) {
                    $mixedResult = "avaliable proividers list:";
                    foreach($aProviders as $aProvider)
                        $mixedResult .= "\n- " . $aProvider['provider_name'] . ", \$iProviderId=" . $aProvider['id'];
                }
                break;
        }

        return $mixedResult;
    }
    
    public function getAutomatorsEvent($sUnit, $sAction)
    {
        if(in_array($sUnit, $this->_aExcludeAlertUnits))
            return [];

        return $this->_oDb->getAutomatorsBy([
            'sample' => 'events', 
            'alert_unit' => $sUnit,
            'alert_action' => $sAction,
            'active' => true
        ]);
    }
    public function getAutomatorsScheduler()
    {
        $aAutomators = $this->_oDb->getAutomatorsBy(['sample' => 'schedulers', 'active' => true]);
        foreach($aAutomators as &$aAutomator)
            if(!empty($aAutomator['params']))
                $aAutomator['params'] = json_decode($aAutomator['params'], true);

        return $aAutomators;
    }

    public function callAutomator($sType, $aParams = [])
    {
        $sMethod = '_callAutomator' . bx_gen_method_name($sType);
        if(!method_exists($this, $sMethod))
            return false;

        return $this->$sMethod($aParams);
    }

    protected function _callAutomatorEvent($aParams = [])
    {
        if(!isset($aParams['automator'], $aParams['alert']) || !is_a($aParams['alert'], 'BxDolAlerts'))
            return false;
        
        $oAlert = &$aParams['alert'];

        $this->evalCode($aParams['automator'], true, ['alert' => $oAlert]);
    }

    protected function _callAutomatorScheduler($aParams = [])
    {
        if(!isset($aParams['automator']))
            return false;
        
        $this->evalCode($aParams['automator'], true);
    }
    
    
    public function evalCode($aAutomator, $isWriteLog = true, $aParams = null)
    {
        ob_start();
        set_error_handler("evalErrorHandler");
        try{
             $sCode = '';
             if ($aAutomator['type'] == 'scheduler'){
                 $sCode = $aAutomator['code'] . '; onCron();';
             }
             
             if ($aAutomator['type'] == 'event'){
                 $sCode = $aAutomator['code']. '; onAlert($aParams["alert"]->iObject , $aParams["alert"]->iSender , $aParams["alert"]->aExtras);';  
             }
             
             
             eval($sCode);
            
        }
        catch (Exception $oException) {
            if ($isWriteLog){
                $this->writeLog($oException->getFile() . ':' . $oException->getLine() . ' ' . $oException->getMessage());
            }
            return $oException->getMessage();
        } finally {
             $sOutput = ob_get_clean();
             if ($sOutput != '')
                return "Eval error: " . $sOutput;
            restore_error_handler();
        }
    }
    
    private function writeLog($sString)
	{
        bx_log('sys_agents', $sString);
	}
}

class EvalException extends Exception {}

function evalErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return false;
    }
    throw new EvalException($errstr, $errno);
}
