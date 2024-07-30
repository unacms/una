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
    protected $_sEndpointRunsCheck;
    protected $_sEndpointMessages;

    protected $_sEndpointAssistants;
    protected $_sEndpointAssistantsDelete;

    protected $_sEndpointFiles;
    protected $_sEndpointFilesRetrieve;
    protected $_sEndpointFilesDelete;

    protected $_sEndpointVectorStores;
    protected $_sEndpointVectorStoresDelete;

    protected $_sEndpointVectorStoresFiles;
    protected $_sEndpointVectorStoresFilesRetrieve;
    protected $_sEndpointVectorStoresFilesDelete;   

    protected $_sEndpointChat;

    public function __construct($aModel)
    {
        $this->_sName = self::$NAME;

        parent::__construct($aModel);

        $this->_sEndpoint = 'https://api.openai.com/v1/threads';
        $this->_sEndpointRuns = $this->_sEndpoint . '/%s/runs';
        $this->_sEndpointRunsCheck = $this->_sEndpoint . '/%s/runs/%s';
        $this->_sEndpointMessages = $this->_sEndpoint . '/%s/messages';

        $this->_sEndpointAssistants = 'https://api.openai.com/v1/assistants';
        $this->_sEndpointAssistantsDelete = $this->_sEndpointAssistants . '/%s';

        $this->_sEndpointFiles = 'https://api.openai.com/v1/files';
        $this->_sEndpointFilesRetrieve = $this->_sEndpointFiles . '/%s';
        $this->_sEndpointFilesDelete = $this->_sEndpointFilesRetrieve;

        $this->_sEndpointVectorStores = 'https://api.openai.com/v1/vector_stores';
        $this->_sEndpointVectorStoresDelete = $this->_sEndpointVectorStores . '/%s';

        $this->_sEndpointVectorStoresFiles = $this->_sEndpointVectorStores . '/%s/files';
        $this->_sEndpointVectorStoresFilesRetrieve = $this->_sEndpointVectorStoresFiles . '/%s';
        $this->_sEndpointVectorStoresFilesDelete = $this->_sEndpointVectorStoresFiles . '/%s';

        $this->_sEndpointChat = 'https://api.openai.com/v1/chat/completions';
    }
    
    public function getResponseText($sPrompt, $sMessage)
    {
        $aMessages = [
            ['role' => 'system', 'content' => $sPrompt],
            ['role' => 'user', 'content' => $sMessage]
        ];

        $sResponse = $this->callChat($aMessages);
        if($sResponse == 'false')
            return false;
        
        return $sResponse;
    }

    public function getResponseInit($sType, $sMessage, $aParams = [])
    {
        $aResponse = $this->call(['messages' => [['role' => 'user', 'content' => $sMessage]]]);
        if(!isset($aResponse['id'], $aResponse['object']) || $aResponse['object'] != 'thread')
            return false;

        $sThreadId = $aResponse['id'];
        $sAssistantId = isset($aParams['assistant_id']) ? $aParams['assistant_id'] : $this->_getAssistantId($sType . '_init');

        if(!$this->callRuns($sThreadId, ['assistant_id' => $sAssistantId]))
            return false;

        $sResponse = $this->getMessages($sThreadId);

        $mixedResult = [];
        switch($sType) {
            case BX_DOL_AI_AUTOMATOR_EVENT:
                $aResponse = json_decode($sResponse, true);

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

            case BX_DOL_AI_AUTOMATOR_WEBHOOK:
            case BX_DOL_AI_ASSISTANT:
                $mixedResult = [
                    'params' => [
                        'thread_id' => $sThreadId,
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

        $sAssistantId = isset($aParams['assistant_id']) ? $aParams['assistant_id'] : $this->_getAssistantId($sType);
        if(!$this->callRuns($sThreadId, ['assistant_id' => $sAssistantId]))
            return false;

        return $this->getMessages($sThreadId);
    }

    public function getAssistant($aParams = [])
    {
        $aResponseVs = $this->callVectorStores(['name' => $aParams['name']]);
        if($aResponseVs === false)
            return false;
        
        $sVectorStoreId = $aResponseVs['id'];

        $aResponseAsst = $this->callAssistants([
            'model' => $this->_sName, 
            'name' => $aParams['name'], 
            'instructions' => $aParams['prompt'], 
            'tools' => [
                ['type' => 'file_search']
            ],
            'tool_resources' => [
                'file_search' => [
                    'vector_store_ids' => [$sVectorStoreId]
                ]
            ]
        ]);
        if($aResponseAsst === false)
            return false;
        
        $sAssistantId = $aResponseAsst['id'];

        return [
            'vector_store_id' => $sVectorStoreId,
            'assistant_id' => $sAssistantId
        ];
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

        $aResponse = $this->_call(sprintf($this->_sEndpointRuns, $sThreadId), $aData);
        if($aResponse !== false && isset($aResponse['id'])) {
            $sRunId = $aResponse['id'];

            while($aResponse['status'] != 'completed') {
                sleep(2);

                $aResponse = $this->_call(sprintf($this->_sEndpointRunsCheck, $sThreadId, $sRunId), [], 'get');
            }
        }

        return $aResponse;
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

    /**
     * Create a vector store.
     * 
     * @param type $aParams - should have 'name'
     * @return boolean
     */
    public function callVectorStores($aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_vs']) && is_array($this->_aParams['call_vs']))
            $aData = array_merge($aData, $this->_aParams['call_vs']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call($this->_sEndpointVectorStores, $aData);
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'vector_store')
            return false;

        return $mixedResponse;
    }
    
    public function callVectorStoresDelete($sVectorStoreId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_vs_delete']) && is_array($this->_aParams['call_vs_delete']))
            $aData = array_merge($aData, $this->_aParams['call_vs_delete']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointVectorStoresDelete, $sVectorStoreId), [], 'DELETE');
        if(empty($mixedResponse) || !is_array($mixedResponse) || !$mixedResponse['deleted'])
            return false;

        return $mixedResponse;
    }

    /**
     * Create a vector store file by attaching a File to a vector store.
     * 
     * @param type $sVectorStoreId
     * @param type $aParams - should have 'file_id'
     * @return boolean
     */
    public function callVectorStoresFiles($sVectorStoreId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_vs_files']) && is_array($this->_aParams['call_vs_files']))
            $aData = array_merge($aData, $this->_aParams['call_vs_files']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointVectorStoresFiles, $sVectorStoreId), $aData);
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'vector_store.file')
            return false;

        return $mixedResponse;
    }
    
    public function callVectorStoresFilesList($sVectorStoreId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_vs_flist']) && is_array($this->_aParams['call_vs_flist']))
            $aData = array_merge($aData, $this->_aParams['call_vs_flist']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointVectorStoresFiles, $sVectorStoreId), $aData, 'get');
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'list')
            return false;

        return $mixedResponse['data'];
    }

    public function callVectorStoresFilesRetrieveFile($sVectorStoreId, $sFileId)
    {
        return $this->_call(sprintf($this->_sEndpointVectorStoresFilesRetrieve, $sVectorStoreId, $sFileId), [], 'get');
    }
    
    public function callVectorStoresFilesDelete($sVectorStoreId, $sFileId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_vs_files_retrieve']) && is_array($this->_aParams['call_vs_files_retrieve']))
            $aData = array_merge($aData, $this->_aParams['call_vs_files_retrieve']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        return $this->_call(sprintf($this->_sEndpointVectorStoresFilesDelete, $sVectorStoreId, $sFileId), [], 'DELETE');
    }

    public function callAssistants($aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_assts']) && is_array($this->_aParams['call_assts']))
            $aData = array_merge($aData, $this->_aParams['call_assts']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call($this->_sEndpointAssistants, $aData);
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'assistant')
            return false;

        return $mixedResponse;
    }
    
    public function callAssistantsDelete($sAsstId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_assts_delete']) && is_array($this->_aParams['call_assts_delete']))
            $aData = array_merge($aData, $this->_aParams['call_assts_delete']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointAssistantsDelete, $sAsstId), $aData, 'DELETE');
        if(empty($mixedResponse) || !is_array($mixedResponse) || !$mixedResponse['deleted'])
            return false;

        return $mixedResponse;
    }

    public function callFiles($aFile, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_files']) && is_array($this->_aParams['call_files']))
            $aData = array_merge($aData, $this->_aParams['call_files']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        if(empty($aData['purpose']))
            $aData['purpose'] = 'assistants';

        $sName = !empty($aFile['name']) ? $aFile['name'] : 'file_' . time() . '.txt';
        $sMime = !empty($aFile['mime']) ? $aFile['mime'] : 'text/plain';
        $aData['file'] = new CURLStringFile($aFile['content'], $sName, $sMime);

        $mixedResponse = $this->_callFiles($this->_sEndpointFiles, $aData);
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'file')
            return false;

        return $mixedResponse;
    }

    public function callFilesRetrieve($sFileId)
    {
        $mixedResponse = $this->_callFiles(sprintf($this->_sEndpointFilesRetrieve, $sFileId), [], 'get');
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'file')
            return false;

        return $mixedResponse;
    }

    public function callFilesDelete($sFileId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['call_files_delete']) && is_array($this->_aParams['call_files_delete']))
            $aData = array_merge($aData, $this->_aParams['call_files_delete']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_callFiles(sprintf($this->_sEndpointFilesDelete, $sFileId), $aData, 'DELETE');
        if(empty($mixedResponse) || !is_array($mixedResponse) || $mixedResponse['object'] != 'file')
            return false;

        return $mixedResponse;
    }

    public function callChat($aMessages, $aParams = [])
    {
        $aData = [
            'model' => $this->_sName,
            'messages' => $aMessages
        ];

        if(!empty($this->_aParams['call']) && is_array($this->_aParams['call']))
            $aData = array_merge($aData, $this->_aParams['call']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $sResponse = bx_file_get_contents($this->_sEndpointChat, $aData, "post-json", [
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

    public function getMessages($sThreadId, $aParams = [])
    {
        $aData = [];
        if(!empty($this->_aParams['get_messages']) && is_array($this->_aParams['get_messages']))
            $aData = array_merge($aData, $this->_aParams['get_messages']);
        if(!empty($aParams) && is_array($aParams))
            $aData = array_merge($aData, $aParams);

        $mixedResponse = $this->_call(sprintf($this->_sEndpointMessages, $sThreadId), $aData, "get");
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

    protected function _callFiles($sEndpoint, $aData, $sMethod = "post-raw")
    {
        $sResponse = bx_file_get_contents($sEndpoint, $aData, $sMethod, [
            "Authorization: Bearer " . $this->_sKey
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
        return isset($this->_aParams['assistants'][$sType]) ? $this->_aParams['assistants'][$sType] : '';
    }
}
