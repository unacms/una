<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAIModelGpt35 extends BxDolAIModel
{
    public static $NAME = 'gpt-3.5-turbo';

    protected $_sEndpoint;
    protected $_sPathInst;

    public function __construct($aModel)
    {
        $this->_sName = self::$NAME;

        parent::__construct($aModel);

        $this->_sEndpoint = "https://api.openai.com/v1/chat/completions";
        $this->_sPathInst = BX_DIRECTORY_PATH_ROOT . 'ai/instructions/';
    }

    public function getResponseInit($sType, $sMessage, $aParams = [])
    {
        $aMessages = [
            ['role' => 'system', 'content' => $this->_getInstructions($sType . '_init')],
            ['role' => 'user', 'content' => $sMessage]
        ];

        $sResponse = $this->call($aMessages);
        if($sResponse == 'false')
            return false;

        $mixedResult = [];
        switch($sType) {
            case BX_DOL_AI_AUTOMATOR_EVENT:
                $aResponse = json_decode($sResponse, true);

                $mixedResult = [
                    'alert_unit' => $aResponse['alert_unit'],
                    'alert_action' => $aResponse['alert_action'],
                    'params' => [
                        'trigger' => $aResponse['trigger']
                    ]
                ];
                break;

            case BX_DOL_AI_AUTOMATOR_SCHEDULER:
                $mixedResult = [
                    'params' => [
                        'scheduler_time' => $sResponse
                    ]
                ];
                break;

            case BX_DOL_AI_AUTOMATOR_WEBHOOK:
                break;
        }
        

        return $mixedResult;
    }

    public function getResponse($sType, $mixedMessage, $aParams = [])
    {
        $aMessages = [
            ['role' => 'system', 'content' => $this->_getInstructions($sType, true)]
        ];
        
        if(is_array($mixedMessage)) {
            foreach($mixedMessage as $aMessage)
                $aMessages[] = [
                    'role' => $aMessage['ai'] ? 'assistant' : 'user',
                    'content' => $aMessage['content'] . (!$aMessage['ai'] ? ' return only function, without explanation or false if you havn`t enougth data' : '')
                ];
        }
        else
            $aMessages[] = ['role' => 'user', 'content' => $mixedMessage];

        $sResponse = $this->call($aMessages);
        if($sResponse == 'false')
            return false;

        return $sResponse;
    }

    public function call($aMessages, $aParams = [])
    {
        $aData = [
            'model' => $this->_sName,
            'messages' => $aMessages
        ];

        if(!empty($this->_aParams['call']) && is_array($this->_aParams['call']))
            $aData = array_merge($aData, $this->_aParams['call']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $sResponse = bx_file_get_contents($this->_sEndpoint, $aData, "post-json", [
            "Authorization: Bearer " . $this->_sKey, 
            'Content-Type: application/json', 
            'OpenAI-Beta: assistants=v1'
        ]);

        $aResponse = json_decode($sResponse, true);
        if(isset($aResponse['error'])) {
            $this->_log($aResponse['error']);
            return 'false';
        }

        return trim(str_replace(['```json', '```php', '```'], '', $aResponse['choices'][0]['message']['content']));
    }

    protected function _getInstructions($sType, $bIncludeCommon = false)
    {
        $sResult = file_get_contents($this->_sPathInst . $sType . '.html');
        if($bIncludeCommon)
            $sResult .= file_get_contents($this->_sPathInst. 'common.html');

        return $sResult;
    }
}
