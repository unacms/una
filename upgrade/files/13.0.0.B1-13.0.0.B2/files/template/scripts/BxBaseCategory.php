<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Category objects representation.
 * @see BxDolCategory
 */
class BxBaseCategory extends BxDolCategory
{
    protected $_oTemplate;
    protected $_sBrowseUrl;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

		$this->_sBrowseUrl = bx_append_url_params('searchKeyword.php', array(
			'cat' => '{category}',
			'keyword' => '{keyword}'
		), true, ['{category}', '{keyword}']) . '{sections}' . '{context}';
    }

    public function getCategoryTitle($sValue)
    {
    	$a = BxDolForm::getDataItems($this->_aObject['list_name']);
        if (!$a || !isset($a[$sValue]))
            return '';

		return $a[$sValue];
    }

    public function getCategoryUrl($sValue, $aParams = [])
    {
        return BX_DOL_URL_ROOT . bx_replace_markers($this->_sBrowseUrl, array(
        	'category' => rawurlencode($this->getObjectName()),
        	'keyword' => rawurlencode($sValue),
    		'sections' => $this->_aObject['search_object'] ? '&section[]=' . rawurlencode($this->_aObject['search_object']) : '',
            'context' => isset($aParams['context_id']) ? '&context_id=' . $aParams['context_id'] : ''
        ));
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
    public function getCategoriesList($bDisplayEmptyCats = true, $bAsArray = false)
    {
        $aContextInfo = bx_get_page_info();

        $mProfileContextId = false;
        if ($aContextInfo !== false)
            $mProfileContextId = $aContextInfo['context_profile_id'];
        
        $a = BxDolForm::getDataItems($this->_aObject['list_name']);
        if (!$a)
            return $bAsArray ? array() : '';

        $aVars = array('bx_repeat:cats' => array());
        foreach ($a as $sValue => $sName) {
            if (!is_numeric($sValue) && !$sValue)
                continue;
            
            $iNum = $this->getItemsNum($sValue, ['context_id' => $mProfileContextId]);
            if (!$bDisplayEmptyCats && !$iNum)
                continue;
            
            $aVars['bx_repeat:cats'][] = array(
                'url' => $mProfileContextId? $this->getCategoryUrl($sValue, ['context_id' => $mProfileContextId]) : $this->getCategoryUrl($sValue),
                'name' => $sName,
                'value' => $sValue,
                'num' => $iNum,
            );
        }

        if ($bAsArray)
            return $aVars;

        if (!$aVars['bx_repeat:cats'])
            return '';

        return $this->_oTemplate->parseHtmlByName('category_list.html', $aVars);
    }
}

/** @} */
