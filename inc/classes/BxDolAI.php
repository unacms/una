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
    protected $_sPathInst;
    protected $_iProfileId;
    
    protected $_aExcludeAlertUnits;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oDb = new BxDolAIQuery();
        $this->_sPathInst = BX_DIRECTORY_PATH_ROOT . 'ai/instructions/';
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

    public function getAutomatorInstructions($sType, $bIncludeCommon = false)
    {
        $sResult = file_get_contents($this->_sPathInst . $sType . '.html');
        if($bIncludeCommon)
            $sResult .= file_get_contents($this->_sPathInst. 'common.html');

        return $sResult;
    }
    
    public function getModel($iId)
    {
        $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $iId]);
        if(!empty($aModel['params']))
            $aModel['params'] = json_decode($aModel['params'], true);

        return $aModel;
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

    public function chat($sEndpoint, $sModel, $sApiKey, $aParams, $aMessages)
    {
        $aData = [
            'model' => $sModel,
            'messages' => $aMessages
        ];

        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $sRv = bx_file_get_contents($sEndpoint, $aData, "post-json", ["Authorization: Bearer ".$sApiKey, 'Content-Type: application/json', 'OpenAI-Beta: assistants=v1']);
        $aRv = json_decode($sRv, true);

        if(isset($aRv['error'])) {
            //TODO: log error in AI related log file.
            return 'false';
        }

        return trim(str_replace(['```json', '```php', '```'], '', $aRv['choices'][0]['message']['content']));
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

        //TODO: Call the code in appropriate way using data from $oAlert
        eval($aParams['automator']['code']);
    }

    protected function _callAutomatorScheduler($aParams = [])
    {
        if(!isset($aParams['automator']))
            return false;

        //TODO: Call the code in appropriate way
        eval($aParams['automator']['code']);
    }
}