<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolSockets extends BxDolFactory implements iBxDolSingleton
{
    protected $_sHost;
    protected $_sPort;
    protected $_sScheme;
    protected $_sIsEnabled = false;
    
    protected function __construct()
    {
        parent::__construct();
        if (getParam('sys_sockets_type') != 'sys_sockets_disabled' && trim(getParam('sys_sockets_url')) != ''){
            $this->_sIsEnabled = true;
            $a = parse_url(getParam('sys_sockets_url'));
            $this->_sHost = $a['host'];
            $this->_sPort = $a['port'];
            $this->_sScheme = $a['scheme'];
        }    
    }

    static public function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses']['BxDolSockets'])){
            $GLOBALS['bxDolClasses']['BxDolSockets'] = new BxDolSockets();
            if (getParam('sys_sockets_type') == 'sys_sockets_soketi')
                $GLOBALS['bxDolClasses']['BxDolSockets'] = new BxDolSocketsSoketi();
        }
            
        return $GLOBALS['bxDolClasses']['BxDolSockets'];
    }
    
    public function isEnable()
    {
        return $this->_sIsEnabled;
    }
    
    public function sendEvent($sModule, $iContentId, $sEvent, $sMessage)
    {
        return;
    }
    
    public function getInitJsCode()
    {
        return 'null';
    }
    
    public function getSubscribeJsCode($sModule, $iContentId, $sEvent, $sCb)
    {
        return '';
    }
    
}

/** @} */
