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

    protected function __construct()
    {
        parent::__construct();
    }
    
    public function sendEvent($sModule, $iContentId, $sEvent, $sMessage)
    {
        $oPusher = new Pusher\Pusher(getParam('sys_sockets_key'), getParam('sys_sockets_secret'), getParam('sys_sockets_app_id'), [
            'host' => $this->_sHost,
            'port' => $this->_sPort,
            'scheme' => $this->_sScheme,
            'encrypted' => true,
            'useTLS' => false,
        ]);
        $b = $oPusher->trigger($sModule . '_' . $iContentId, $sEvent, $sMessage);
        
    }
    
    public function getInitJsCode()
    {
        return " new Pusher('". getParam('sys_sockets_key') ."', {
            wsHost: '" . $this->_sHost . "',
            wsPort: " . $this->_sPort . ",
            forceTLS: false, 
            enabledTransports: ['ws', 'wss']
        });";
    }
    
    public function getSubscribeJsCode($sModule, $iContentId, $sEvent, $sCb)
    {
        return " $(document).ready(function() {oBxDolPage.socketsSubscribe('" . $sModule . "', '" . $iContentId . "', '" . $sEvent . "', function(data) {" . $sCb . "})})";
    }
}

/** @} */
