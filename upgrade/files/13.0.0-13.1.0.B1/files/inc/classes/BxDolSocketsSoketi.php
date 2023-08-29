<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolSocketsSoketi extends BxDolSockets
{
    protected $_sKey;

    protected $_sJsClass;
    protected $_sJsObject;

    protected function __construct()
    {
        parent::__construct();
        
        $this->_sKey = getParam('sys_sockets_key');
        
        $this->_sJsClass = 'BxDolSockets';
        $this->_sJsObject = 'oBxDolSockets';
    }
    
    public function sendEvent($sSocket, $iContentId, $sEvent, $sMessage)
    {
        try {
            $oPusher = new Pusher\Pusher($this->_sKey, getParam('sys_sockets_secret'), getParam('sys_sockets_app_id'), [
                'host' => $this->_sHost,
                'port' => $this->_sPort,
                'scheme' => $this->_sScheme,
                'encrypted' => true,
                'useTLS' => false,
            ]);
            $b = $oPusher->trigger($sSocket . '_' . $iContentId, $sEvent, $sMessage);
        }
        catch (Exception $oException) {
            $this->writeLog($oException->getFile() . ':' . $oException->getLine() . ' ' . $oException->getMessage());
            return false;
        }
    }

    public function getJsCode()
    {
        $sMask = "{var} {object} = new {class}({params});";
        $aParams = [
            'sKey' => $this->_sKey,
            'sHost' => $this->_sHost,
            'sPort' => $this->_sPort,
        ];

        return bx_replace_markers($sMask, [
            'var' => 'var',
            'object' => $this->_sJsObject, 
            'class' => $this->_sJsClass,
            'params' => json_encode($aParams)
        ]);
    }
}

/** @} */
