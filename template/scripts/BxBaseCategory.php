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

    protected $_oModule;
    protected $_bModule;

    protected $_sBrowseAllUrl;
    protected $_sBrowseUrl;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_oModule = null;
        if(!empty($this->_aObject['module']))
            $this->_oModule = BxDolModule::getInstance($this->_aObject['module']);
        $this->_bModule = $this->_oModule !== null;

        $this->_sBrowseAllUrl = '';
        $this->_sBrowseUrl = bx_append_url_params('searchKeyword.php', [
            'cat' => '{category}',
            'keyword' => '{keyword}'
        ], true, ['{category}', '{keyword}']) . '{sections}' . '{context}';

        if($this->_bModule) {
            $CNF = &$this->_oModule->_oConfig->CNF;
            
            if(!empty($CNF['URL_HOME']))
                $this->_sBrowseAllUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);

            if(!empty($CNF['URL_CATEGORY']))
                $this->_sBrowseUrl = bx_append_url_params(BxDolPermalinks::getInstance()->permalink($CNF['URL_CATEGORY']), [
                    'category' => '{keyword}'
                ], true, ['{keyword}']);
        }
    }

    public function getCategoryIcon($sValue)
    {
        return 'star';
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
        $s = BX_DOL_URL_ROOT . bx_replace_markers($this->_sBrowseUrl, [
            'category' => rawurlencode($this->getObjectName()),
            'keyword' => rawurlencode($sValue),
            'sections' => $this->_aObject['search_object'] ? '&section[]=' . rawurlencode($this->_aObject['search_object']) : '',
            'context' => isset($aParams['context_id']) ? '&context_id=' . $aParams['context_id'] : ''
        ]);

        if (bx_is_api())
            return bx_api_get_relative_url($s);

        return $s;
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
            return $bAsArray ? [] : '';

        $aVars = [
            'bx_repeat:cats' => [],
            'bx_if:show_all' => [
                'condition' => $this->_sBrowseAllUrl != '',
                'content' => [
                    'url' => $this->_sBrowseAllUrl,
                    'name' => _t('all')
                ]
            ]
        ];

        foreach ($a as $sValue => $sName) {
            if (!is_numeric($sValue) && !$sValue)
                continue;
            
            $iNum = $this->getItemsNum($sValue, ['context_id' => $mProfileContextId]);
            if (!$bDisplayEmptyCats && !$iNum)
                continue;
            
            $aVars['bx_repeat:cats'][] = [
                'url' => $this->getCategoryUrl($sValue, ($mProfileContextId ? ['context_id' => $mProfileContextId] : [])),
                'name' => $sName,
                'value' => $sValue,
                'num' => $iNum,
                'icon' => $this->getCategoryIcon($sValue),
                'selected_class' => $sValue == bx_get('category') ? 'bx-category-list-item-selected' : '',
            ];
        }

        if(bx_is_api())
            return [bx_api_get_block('categories_list',  $aVars['bx_repeat:cats'])];

        if ($bAsArray)
            return $aVars;

        if (!$aVars['bx_repeat:cats'])
            return '';

        return $this->_oTemplate->parseHtmlByName('category_list.html', $aVars);
    }
}

/** @} */
