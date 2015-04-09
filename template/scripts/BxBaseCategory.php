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

    public function getCategoryUrl($sValue)
    {
        $sUrl = BX_DOL_URL_ROOT . 'searchKeyword.php?cat=' . rawurlencode($this->getObjectName()) . '&keyword=' . rawurlencode($sValue);
        if ($this->_aObject['search_object'])
            $sUrl .= '&section[]=' . rawurlencode($this->_aObject['search_object']);
        return $sUrl;
    }

    /**
     * Get link to list all items with the same category
     * @param $sName category title
     * @param $sValue category value
     * @return category name wrapped with A tag
     */
    public function getCategoryLink($sName, $sValue)
    {
        $sUrl = $this->getCategoryUrl($sValue);
        return '<a class="bx-category-link" href="' . $sUrl . '">' . $sName . '</a>';
    }

    /**
     * Get all categories list
     * @param $bDisplayEmptyCats display categories with no items, true by default
     * @return categories list html
     */
    public function getCategoriesList($bDisplayEmptyCats = true)
    {
        $a = BxDolForm::getDataItems($this->_aObject['list_name']);
        if (!$a)
            return '';

        $aVars = array('bx_repeat:cats' => array());
        foreach ($a as $sValue => $sName) {
            if (!$sValue)
                continue;
            $iNum = $this->getItemsNum($sValue);
            if (!$bDisplayEmptyCats && !$iNum)
                continue;
            $aVars['bx_repeat:cats'][] = array(
                'url' => $this->getCategoryUrl($sValue),
                'name' => $sName,
                'value' => $sValue,
                'num' => $iNum,
            );
        }

        if (!$aVars['bx_repeat:cats'])
            return '';

        return $this->_oTemplate->parseHtmlByName('category_list.html', $aVars);
    }
}

/** @} */
