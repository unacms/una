<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

// TODO: reconsider almost all functionality in this file, according to new concept it should NOT be separate page with all tags, every module has its own page with tags

bx_import('BxDolPageView');
bx_import('BxTemplTags');

class BxBaseTagsModule extends BxDolPageView
{
    var $_sPage;
    var $_sTitle;
    var $_sUrl;
    var $_aParam;

    function BxBaseTagsModule($aParam, $sTitle, $sUrl)
    {
        $this->_sPage = 'tags_module';
        $this->_sTitle = $sTitle ? $sTitle : _t('_all_tags');
        $this->_sUrl = $sUrl;
        $this->_aParam = $aParam;
        parent::BxDolPageView($this->_sPage);
    }

    function getBlockCode_Recent($iBlockId)
    {
        $oTags = new BxTemplTags();
        $oTags->getTagObjectConfig();

        return array(
            $oTags->display(
                array(
                    'type' => $this->_aParam['type'],
                    'orderby' => 'recent',
                    'limit' => getParam('tags_show_limit')
                ),
                $iBlockId, '', $this->_sUrl)
        );
    }

    function getBlockCode_All($iBlockId)
    {
        $oTags = new BxTemplTags();
        $oTags->getTagObjectConfig();

        if (!isset($this->_aParam['pagination']))
            $this->_aParam['pagination'] = getParam('tags_perpage_browse');

        return array(
            $oTags->display($this->_aParam, $iBlockId, '', $this->_sUrl),
            array(),
            array(),
            $this->_sTitle
        );
    }
}

