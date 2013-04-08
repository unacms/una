<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolTemplate');
bx_import('BxDolTags');

class BxBaseTags extends BxDolTags {

    var $_sTagTmplName;
    var $_sTagTmplContent;

    function BxBaseTags () {
        parent::BxDolTags();

        $this->_sTagTmplName = 'view_tags.html';
        $this->_sTagTmplContent = '';
    }

    function getTagsView ($aTotalTags, $sHrefTempl) {
        $oSysTemplate = BxDolTemplate::getInstance();

        if (empty($aTotalTags))
            return MsgBox(_t('_Empty'));

        bx_import('BxTemplConfig');
        $oTemplConfig = BxTemplConfig::getInstance();

        $iMinFontSize = $oTemplConfig -> iTagsMinFontSize;
        $iMaxFontSize = $oTemplConfig -> iTagsMaxFontSize;
        $iFontDiff = $iMaxFontSize - $iMinFontSize;

        $iMinRating = min( $aTotalTags );
        $iMaxRating = max( $aTotalTags );

        $iRatingDiff = $iMaxRating - $iMinRating;
        $iRatingDiff = ($iRatingDiff==0)? 1:$iRatingDiff;

        $sCode = '<div class="tags_wrapper">';
        $aUnit = array();
        foreach( $aTotalTags as $sTag => $iCount ) {
            $aUnit['tagSize'] = $iMinFontSize + round( $iFontDiff * ( ( $iCount - $iMinRating ) / $iRatingDiff ) );
            $aUnit['tagHref'] = str_replace( '{tag}', urlencode(title2uri($sTag)), $sHrefTempl);
            $aUnit['countCapt'] = _t( '_Count' );
            $aUnit['countNum'] = $iCount;
            $aUnit['tag'] = htmlspecialchars_adv( $sTag );
            if ($this->_sTagTmplContent)
                $sCode .= $oSysTemplate->parseHtmlByContent($this->_sTagTmplContent, $aUnit);
            else
                $sCode .= $oSysTemplate->parseHtmlByName($this->_sTagTmplName, $aUnit);
        }
        $sCode .= '</div>';
        $sCode .= '<div class="clear_both"></div>';
        return $sCode;
    }

    function getTagsTopMenu ($aParam, $sAction = '')
    {
        $aTopMenu = array();
        $aParamTmp = $aParam;

        foreach ($this->aTagObjects as $sKey => $aTagUnit)
        {
            $sName = _t($aTagUnit['LangKey']);
            $sHref = bx_html_attribute($_SERVER['PHP_SELF']) . "?tags_mode=$sKey" . ($sAction ? '&action=' . $sAction : '');

            if (isset($aParam['filter']) && $aParam['filter'])
            {
                $aParamTmp['type'] = $sKey;
                $sName .= '(' . $this->getTagsCount($aParamTmp) . ')';
                $sHref .= '&filter=' . $aParam['filter'];
            }

            if (isset($aParam['date']) && $aParam['date'])
                $sHref .= '&year=' . $aParam['date']['year'] .
                    '&month=' . $aParam['date']['month'] .
                    '&day=' . $aParam['date']['day'];

            $aTopMenu[$sName] = array('href' => $sHref, 'key' => $sKey, 'dynamic' => true, 'active' => ( $sKey == $aParam['type']));
        }

        return $aTopMenu;
    }

    function getTagsTopMenuHtml ($aParam, $iBoxId, $sAction = '')
    {
        $aItems = array();

        $aTopMenu = $this->getTagsTopMenu($aParam, $sAction);
        foreach ($aTopMenu as $sName => $aItem)
        {
            $aItems[$sName] = array(
                'dynamic' => true,
                'active' => $aItem['active'],
                'href' => $aItem['href']
            );
        }

        return BxDolPageView::getBlockCaptionItemCode($iBoxId, $aItems);
    }

    function getTagsInternalMenuHtml ($aParam, $iBoxId, $sAction = '') {
        $sMenu = '';

        $sMenu = _t('_tags_caption_module') . ' <select onchange="loadDynamicBlock(' . $iBoxId . ', this.value);">';
        $aMenu = $this->getTagsTopMenu($aParam, $sAction);

        foreach ($aMenu as $sName => $aItem) {
            $sMenu .= '<option value="' . $aItem['href'] . '" '.
                ($aItem['key'] == $aParam['type'] ? 'selected' : '') .'>' .
                $sName . '</option>';
        }

        $sMenu .= '</select>';

        return BxDolTemplate::getInstance()->parseHtmlByName('top_block.html', array('code' => $sMenu));
    }

    function display($aParam, $iBoxId, $sAction = '', $sUrl = '')
    {
        $sPaginate = '';

        if (!isset($aParam['type']) || !$aParam['type'])
            return MsgBox(_t( '_Empty' ));

        if (isset($aParam['pagination']) && $aParam['pagination'])
        {
            bx_import('BxDolPaginate');
            $sPageUrl = $sUrl ? $sUrl : bx_html_attribute($_SERVER['PHP_SELF']);
            $sPageUrl .= '?tags_mode=' . $aParam['type'] . '&page={page}&per_page={per_page}';

            if (isset($aParam['filter']) && $aParam['filter'])
                $sPageUrl .= '&filter=' . $aParam['filter'];
            if ($sAction)
                $sPageUrl .= '&action=' . $sAction;
            if (isset($aParam['date']) && $aParam['date'])
            {
                $sPageUrl .= '&year=' . $aParam['date']['year'] .
                    '&month=' . $aParam['date']['month'] .
                    '&day=' . $aParam['date']['day'];
            }

            $aPaginate = array(
                'page_url' => $sPageUrl,
                'info' => true,
                'page_links' => true,
                'on_change_page' => "!loadDynamicBlock($iBoxId, this.href)"
            );

            $aParam['limit'] = $aPaginate['per_page'] = $aParam['pagination'];
            $aPaginate['count'] = $this->getTagsCount($aParam);
            $aPaginate['page'] = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
            $aParam['start'] = $aParam['limit'] * ($aPaginate['page'] - 1);
            if ($aParam['start'] <=0)
                $aParam['start'] = 0;

            $oPaginate = new BxDolPaginate($aPaginate);
            $sPaginate = '<div class="clear_both"></div>'.$oPaginate->getPaginate();
        }

        $sHrefTmpl = $this->getHrefWithType($aParam['type']);
        $aTotalTags = $this->getTagList($aParam);

        if ($aTotalTags)
            return $this->getTagsView($aTotalTags, $sHrefTmpl) . $sPaginate;
        else
            return MsgBox(_t( '_Empty' ));
    }

    function setTemplateName($sTmplName)
    {
        $this->_sTagTmplName = $sTmplName;
    }

    function setTemplateContent($sTmplContent)
    {
        $this->_sTagTmplContent = $sTmplContent;
    }
}

