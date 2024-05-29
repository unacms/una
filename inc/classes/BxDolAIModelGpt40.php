<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAIModelGpt40 extends BxDolAIModel
{
    public static $NAME = 'gpt-4o';

    protected $_sEndpoint;
    protected $_sEndpointRuns;
    protected $_sEndpointMessages;

    public function __construct($aModel)
    {
        $this->_sName = self::$NAME;

        parent::__construct($aModel);

        $this->_sEndpoint = "https://api.openai.com/v1/threads";
        $this->_sEndpointRuns = $this->_sEndpoint . '/%s/runs';
        $this->_sEndpointRunsCheck = $this->_sEndpoint . '/%s/runs/%s';
        $this->_sEndpointMessages = $this->_sEndpoint . '/%s/messages';
    }

    public function getResponseInit($sType, $sMessage, $aParams = [])
    {        
        $aResponse = $this->call(['messages' => [['role' => 'user', 'content' => $sMessage]]]);
        if(!isset($aResponse['id'], $aResponse['object']) || $aResponse['object'] != 'thread')
            return false;

        $sThreadId = $aResponse['id'];

        $aResponse = $this->callRuns($sThreadId, [
            'assistant_id' => $this->_getAssistantId($sType . '_init')
        ]);

        $sRunId = $aResponse['id'];
        while($aResponse['status'] != 'completed') {
            sleep(2);

            $aResponse = $this->callRunsCheck($sThreadId, $sRunId);
        }

        $sResponse = $this->getMessages($sThreadId);

        $mixedResult = [];
        switch($sType) {
            case BX_DOL_AI_AUTOMATOR_EVENT:
                //TODO: previously we had an array here with 'alert_unit', 'alert_action' and 'trigger';
                $mixedResult = [
                    'alert_unit' => $aResponse['alert_unit'],
                    'alert_action' => $aResponse['alert_action'],
                    'params' => [
                        'thread_id' => $sThreadId,
                        'trigger' => $aResponse['trigger']
                    ]
                ];
                break;

            case BX_DOL_AI_AUTOMATOR_SCHEDULER:
                $mixedResult = [
                    'params' => [
                        'thread_id' => $sThreadId,
                        'scheduler_time' => $sResponse
                    ]
                ];
                break;
        }

        return $mixedResult;
    }

    public function getResponse($sType, $sMessage, $aParams = [])
    {
        if(empty($aParams['thread_id']))
            return false;

        if(is_array($sMessage))
            $sMessage = end($sMessage)['content'];

        $sThreadId = $aParams['thread_id'];
        if(!$this->callMessages($sThreadId, ['role' => 'user', 'content' => $sMessage]))
            return false;

        $aResponse = $this->callRuns($sThreadId, [
            'assistant_id' => $this->_getAssistantId($sType)
        ]);

        $sRunId = $aResponse['id'];
        while($aResponse['status'] != 'completed') {
            sleep(2);

            $aResponse = $this->callRunsCheck($sThreadId, $sRunId);
        }

        return $this->getMessages($sThreadId);
    }

    public function call($aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call']) && is_array($this->_aParams['call']))
            $aData = array_merge($aData, $this->_aParams['call']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        return $this->_call($this->_sEndpoint, $aData);
        
    }

    public function callRuns($sThreadId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_runs']) && is_array($this->_aParams['call_runs']))
            $aData = array_merge($aData, $this->_aParams['call_runs']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        return $this->_call(sprintf($this->_sEndpointRuns, $sThreadId), $aData);
    }
    
    public function callRunsCheck($sThreadId, $sRunId)
    {
        return $this->_call(sprintf($this->_sEndpointRunsCheck, $sThreadId, $sRunId), [], 'get');
    }
    
    public function callMessages($sThreadId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_messages']) && is_array($this->_aParams['call_messages']))
            $aData = array_merge($aData, $this->_aParams['call_messages']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointMessages, $sThreadId), $aData);
        if($mixedResponse !== false)
            $mixedResponse = $mixedResponse['content'][0]['text']['value'];

        return $mixedResponse;
    }

    public function getMessages($sThreadId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['get_messages']) && is_array($this->_aParams['get_messages']))
            $aData = array_merge($aData, $this->_aParams['get_messages']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointMessages, $sThreadId), $aData, "get-json");
        if($mixedResponse !== false)
            $mixedResponse = trim(str_replace(['```json', '```php', '```'], '', $mixedResponse['data'][0]['content'][0]['text']['value']));

        return $mixedResponse;
    }

    protected function _call($sEndpoint, $aData, $sMethod = "post-json")
    {
        $sResponse = bx_file_get_contents($sEndpoint, $aData, $sMethod, [
            "Authorization: Bearer " . $this->_sKey, 
            'Content-Type: application/json', 
            'OpenAI-Beta: assistants=v2'
        ]);

        $aResponse = json_decode($sResponse, true);
        if(isset($aResponse['error'])) {
            $this->_log($aResponse['error']);
            return false;
        }

        return $aResponse;
    }

    protected function _getAssistantId($sType)
    {
        return $this->_aParams['assistants'][$sType];
    }
}
