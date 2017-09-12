<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

bx_import('BxDolForm');

class BxDolSearchExtended extends BxDolFactory implements iBxDolFactoryObject
{
    public static $SEARCHABLE_TYPES = array(
        'text', 'textarea', 'number', 
    	'select', 'radio_set',
        'checkbox_set', 'select_multiple',
    	'checkbox', 'switcher'
    );

    public static $TYPE_TO_TYPE_SEARCH = array(
        'text' => array('text'),
        'textarea' => array('text'),
        'number' => array('text'),
        'text_auto' => array('text_auto'),
        'select' => array('checkbox_set', 'select_multiple', 'select'),
        'radio_set' => array('checkbox_set', 'select_multiple', 'select'),
    	'checkbox_set' => array('checkbox_set', 'select_multiple', 'select'),
    	'select_multiple' => array('checkbox_set', 'select_multiple', 'select'),
        'checkbox' => array('checkbox', 'switcher'),
    	'switcher' => array('checkbox', 'switcher'),
    );

    public static $TYPE_TO_OPERATOR = array(
    	'text' => array('like', '='),
        'textarea' => array('like', '='),
        'number' => array('='),
        'text_auto' => array('in'),
        'select' => array('in'),
        'radio_set' => array('in'),
    	'checkbox_set' => array('and'), 
    	'select_multiple' => array('and'),
        'checkbox' => array('='),
    	'switcher' => array('='),
    );

    protected $_oDb;

    protected $_sObject;
    protected $_aObject;


    /**
     * Constructor
     * @param $aObject array of search options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_oDb = new BxDolSearchExtendedQuery($this->_aObject);

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
    }

    /**
     * Get editor object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject, $oTemplate = false)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject];

        $aObject = BxDolSearchExtendedQuery::getSearchObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplSearchExtended';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }

        $o = new $sClass($aObject, $oTemplate);
        return ($GLOBALS['bxDolClasses']['BxDolSearchExtended!' . $sObject] = $o);
    }

    static public function actionGetAuthors()
    {
        $aResult = BxDolService::call('system', 'profiles_search', array(bx_get('term')), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo json_encode($aResult);
    }

    public function isEnabled()
    {
        return isset($this->_aObject['active']) && (int)$this->_aObject['active'] != 0;
    }

    public function clean()
    {
        return $this->_oDb->deleteFields(array('object' => $this->_sObject)) !== false;
    }

    public function reset()
    {
        if(!$this->clean())
            return false;

        $this->_aObject['fields'] = BxDolSearchExtendedQuery::getSearchFields($this->_aObject);
        return true;
    }
}

/** @} */
