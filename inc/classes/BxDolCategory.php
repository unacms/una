<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section category Category
 * @ref BxDolCategory
 */

/**
 * Categories object, make to look form lists as categories by making it clickable and display search results with all contents with the same category
 */
class BxDolCategory extends BxDol implements iBxDolFactoryObject
{
    protected $_sObject;
    protected $_aObject;

    /**
     * Constructor
     * @param $aObject array of editor options
     */
    public function __construct($aObject)
    {
        parent::__construct();

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;
    }

    /**
     * Get category object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false)
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolCategory!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolCategory!'.$sObject];

        $aObject = BxDolCategoryQuery::getCategoryObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxTemplCategory';
        if (!empty($aObject['override_class_name']))
            $sClass = $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject);

        return ($GLOBALS['bxDolClasses']['BxDolCategory!'.$sObject] = $o);
    }

    /**
     * Get category object instance by form object and list name
     * @param $sObjectForm form object name
     * @patam $sListName list name
     * @return object instance or false on error
     */
    static public function getObjectInstanceByFormAndList($sObjectForm, $sListName)
    {
        $aObject = BxDolCategoryQuery::getCategoryObjectByFormAndList($sObjectForm, $sListName);
        return self::getObjectInstance($aObject['object']);
    }

    /**
     * Get object name 
     */
    public function getObjectName()
    {
        return $this->_sObject;
    }

    /**
     * Set condition for search results object for category object
     * @param $oSearchResult search results object
     * @param $mixedCategory category
     */
    public function setSearchCondition($oSearchResult, $mixedCategory)
    {
        $oSearchResult->aCurrent['restriction']['category_' . $this->_sObject] = array(
            'value' => $mixedCategory,
            'field' => $this->_aObject['field'],
            'operator' => '=',
            'table' => $this->_aObject['table'],
        );
    }
}

/** @} */
