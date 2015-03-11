<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Category objects representation.
 * @see BxDolCategory
 */
class BxBaseCategory extends BxDolCategory
{
    protected $_oTemplate;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function getCategoryUrl($sName, $sValue, $sCategoryObject)
    {
        $sUrl = BX_DOL_URL_ROOT . 'searchKeyword.php?cat=' . rawurlencode($sCategoryObject) . '&keyword=' . rawurlencode($sValue);
        if ($this->_aObject['search_object'])
            $sUrl .= '&section[]=' . rawurlencode($this->_aObject['search_object']);
        return $sUrl;
    }

    /**
     * Get link to list all items with the same category
     */
    public function getCategoryLink($sName, $sValue, $sCategoryObject)
    {
        $sUrl = $this->getCategoryUrl($sName, $sValue, $sCategoryObject);
        return '<a href="' . $sUrl . '">' . $sName . '</a>';
    }

    /**
     * Get all categories list
     */
    public function getCategoriesList()
    {
        // TODO:
    }
}

/** @} */
