<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAIProvider extends BxDol
{
    protected $_iId;
    protected $_sName;
    protected $_sCaption;
    protected $_sPrefix;
    protected $_aOptions;

    public function __construct($aProvider)
    {
        parent::__construct();

        if(empty($aProvider) || !is_array($aProvider) || strcmp($aProvider['name'], $this->_sName) != 0)
            $this->_log("Unexpected value provided for the credentials");

        $this->_oDb = new BxDolAIQuery();

        $this->_iId = (int)$aProvider['id'];
        $this->_sName = $aProvider['name'];
        $this->_sCaption = _t($aProvider['caption']);
        $this->_sPrefix = $aProvider['option_prefix'];

        $this->_aOptions = [];
        if(!empty($aProvider['options']) && is_array($aProvider['options']))
            $this->initOptions($aProvider['options']);
    }

    /**
     * Get provider object instance by provider ID
     * @param $sProvider provider name
     * @return object instance or false on error
     */
    public static function getObjectInstance($iId)
    {
        $sPrefix = 'BxDolAIProvider!';

        if(isset($GLOBALS['bxDolClasses'][$sPrefix . $iId]))
            return $GLOBALS['bxDolClasses'][$sPrefix . $iId];

        $aProvider = BxDolAIQuery::getProviderObject($iId);
        if (!$aProvider || !is_array($aProvider))
            return false;

        $sClass = 'BxDolAIProvider';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }

        $o = new $sClass($aProvider);
        return ($GLOBALS['bxDolClasses'][$sPrefix . $sObject] = $o);
    }

    public function initOptions($aOptions)
    {
    	$this->_aOptions = $aOptions;
    }

    public function getOption($sName)
    {
    	if(substr($sName, 0, strlen($this->_sPrefix)) != $this->_sPrefix)
            $sName = $this->_sPrefix . $sName;

        return isset($this->_aOptions[$sName]) ? $this->_aOptions[$sName]['value'] : '';
    }

    /**
     * Internal methods.
     */
    protected function _call($sRequest, $aParams, $sMethod = 'post-json', $aHeaders = [])
    {}

    protected function _log($sMessage, $bUseLog = false)
    {
        if($bUseLog) {
            //TODO: Use bx_log here.
        }
        else
            throw new Exception($sMessage);

        return false;
    }    
}
