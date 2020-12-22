<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Base class for classes with implement object instance 
 */
abstract class BxDolFactoryObject extends BxDolFactory implements iBxDolFactoryObject
{
	protected $_oDb;
    protected $_oTemplate;
	protected $_sObject;
    protected $_aObject;

    protected function __construct($aObject, $oTemplate = null, $sDbClassName = '')
    {
        parent::__construct();

        $this->_aObject = $aObject;
        $this->_sObject = $aObject['object'];
        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
        if ($sDbClassName)
            $this->_oDb = new $sDbClassName($this->_aObject);
    }

   /**
     * Get object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    public static function getObjectInstanceByClassNames($sObject, $oTemplate, $sClassName, $sDbClassName)
    {
        if(isset($GLOBALS['bxDolClasses'][$sClassName . '!' . $sObject]))
            return $GLOBALS['bxDolClasses'][$sClassName . '!' . $sObject];

        $aObject = $sDbClassName::getObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = $sClassName;
        if(!empty($aObject['class_name']) || !empty($aObject['override_class_name'])) {
            $sClass = !empty($aObject['class_name']) ? $aObject['class_name'] : $aObject['override_class_name'];
            if(!empty($aObject['class_file']) || !empty($aObject['override_class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . (!empty($aObject['class_file']) ? $aObject['class_file'] : $aObject['override_class_file']));
        }        

        $o = new $sClass($aObject, $oTemplate);
        return ($GLOBALS['bxDolClasses'][$sClassName . '!' . $sObject] = $o);
    }

    /**
     * Get current object name
     */
    public function getObjectName()
    {
        return $this->_aObject['object'];
    }
}

/** @} */
