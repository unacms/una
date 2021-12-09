<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCategories extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = new BxDolCategoriesQuery();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolCategories();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getData($aParams = array())
    {
        return $this->_oDb->getData($aParams);
    }
    
    public function delete($sModule, $iObject)
    {
        return $this->_oDb->delete($sModule, $iObject);
    }
    
    public function add($sModule, $iProfileId, $sValue, $iObject, $bAutoActivation)
    {
        return $this->_oDb->add($sModule, $iProfileId, $sValue, $iObject, $bAutoActivation);
    }
    
    public function getUrl ($sModule, $sValue, $sAddParams = '')
    {
        return  BX_DOL_URL_ROOT . 'searchKeyword.php?keyword=' . rawurlencode($sValue) . '&cat=multi&section=' . $sModule . $sAddParams;
    }
}

/** @} */
