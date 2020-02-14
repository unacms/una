<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Proxy class for module classes, now only module template class is proxied.
 * It's possible to override any module template method call by making a response 
 * for 'module_template_method_call' alert.
 */ 
class BxDolModuleProxy
{
    private $_oProxifiedObject;
    private $_sObjectType;

    function __construct($sObjectType, $oProxifiedObject)
    {
        $this->_sObjectType = $sObjectType;
        $this->_oProxifiedObject = $oProxifiedObject;
    }
    
    public function __call($sMethodName, $aArguments)
    {
        if (is_callable(array($this->_oProxifiedObject, $sMethodName))) {

            // check if we need to include css&js files
            if ('module_template' == $this->_sObjectType && !empty($this->_oProxifiedObject->aMethodsToCallAddJsCss)) {
                foreach ($this->_oProxifiedObject->aMethodsToCallAddJsCss as $s) {
                    if (0 === strpos($sMethodName, $s)) {
                        $this->_oProxifiedObject->addCssJs();
                        break;
                    }
                }
            }
            
            $oModule = ('module_template' == $this->_sObjectType ? $this->_oProxifiedObject->getModule() : null);

            // make it possible to override the call or arguments
            $res = null;
            bx_alert($this->_sObjectType . '_method_call', $sMethodName, 0, 0, array('module' => $oModule, 'args' => &$aArguments, 'override_result' => &$res));
            if (null !== $res)
                return $res;

            // call original method
            return call_user_func_array(array($this->_oProxifiedObject, $sMethodName), $aArguments);
        }
        else {
            $sClass = get_class($this->_oProxifiedObject);
            trigger_error('Method ' . $sMethodName . ' was not found for the class ' . $sClass, E_USER_ERROR);
        }
    }

    public function getClassName()
    {
        return get_class($this->_oProxifiedObject);
    }

    public function isMethodExists($s)
    {
        return method_exists($this->_oProxifiedObject, $s);
    }

    /**
     * Dirty fix for pass by reference to BxTimelineTemplate::getData
     */
    public function getData(&$aEvent, $aBrowseParams = array())
    {
        return $this->_oProxifiedObject->getData($aEvent, $aBrowseParams);
    }

    /**
     * Dirty fix for pass by reference to BxNtfsTemplate::getPost
     */
    public function getPost(&$aEvent, $aBrowseParams = array())
    {
        return $this->_oProxifiedObject->getPost($aEvent, $aBrowseParams);
    }
    /**
     * Dirty fix for pass by reference to BxNtfsTemplate::getNotificationEmail
     */
    public function getNotificationEmail($iRecipient, &$aEvent)
    {
        return $this->_oProxifiedObject->getNotificationEmail($iRecipient, $aEvent);
    }
    /**
     * Dirty fix for pass by reference to BxNtfsTemplate::getNotificationPush
     */
    public function getNotificationPush($iRecipient, &$aEvent)
    {
        return $this->_oProxifiedObject->getNotificationPush($iRecipient, $aEvent);
    }
}

/** @} */
