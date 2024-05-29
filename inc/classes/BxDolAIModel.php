<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAIModel extends BxDol
{
    protected $_oDb;
    protected $_iId;
    protected $_sName;
    protected $_sCaption;
    protected $_sKey;
    protected $_aParams;

    public function __construct($aModel)
    {
        parent::__construct();

        if(empty($aModel) || !is_array($aModel) || strcmp($aModel['name'], $this->_sName) != 0)
            $this->_log("Unexpected value provided for the credentials");

        $this->_oDb = new BxDolAIQuery();

        $this->_iId = (int)$aModel['id'];
        $this->_sName = $aModel['name'];
        $this->_sCaption = _t($aModel['title']);
        $this->_sKey = $aModel['key'];
        $this->_aParams = !empty($aModel['params']) ? json_decode($aModel['params'], true) : [];
    }

    /**
     * Get model object instance by model name
     * @param $sName model name
     * @return object instance or false on error
     */
    public static function getObjectInstance($iId)
    {
        $sPrefix = 'BxDolAIModel!';

        if(isset($GLOBALS['bxDolClasses'][$sPrefix . $iId]))
            return $GLOBALS['bxDolClasses'][$sPrefix . $iId];

        $aModel = BxDolAIQuery::getModelObject($iId);
        if(!$aModel || !is_array($aModel))
            return false;

        $sClass = 'BxDolAIModel';
        if(!empty($aModel['class_name'])) {
            $sClass = $aModel['class_name'];
            if(!empty($aModel['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aModel['class_file']);
        }

        $o = new $sClass($aModel);
        return ($GLOBALS['bxDolClasses'][$sPrefix . $iId] = $o);
    }
    
    public function getParams()
    {
        return $this->_aParams;
    }

    public function setParams($aParams)
    {
        if(empty($aParams) || !is_array($aParams))
            return;

        $this->_aParams = array_merge($this->_aParams, $aParams);
    }

    public function getResponseInit($sType, $aMessage, $aParams = [])
    {
        // Should be overwritten to get init call response.
    }

    public function getResponse($sType, $aMessage, $aParams = [])
    {
        // Should be overwritten to get call response.
    }

    /**
     * Internal methods.
     */
    protected function _log($mixedError, $bUseLog = false)
    {
        if($bUseLog)
            bx_log('sys_agents', $mixedError);
        else {
            $sMessage = 'Error occurred';
            if(is_string($mixedError))
                $sMessage = $mixedError;
            else if(is_array($mixedError) && isset($mixedError['message']))
                $sMessage = $mixedError['message'];

            throw new Exception($sMessage);
        }

        return false;
    }    
}
