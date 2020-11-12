<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxBaseSearch extends BxDolSearch
{
    protected $_oTemplate;
    protected $_sIdForm = 'sys_search_form';
    protected $_sIdResults = 'sys_search_results';
    protected $_sSuffixLiveSearch = '_quick';

    public function __construct($aChoice, $oTemplate)
    {
        if (!is_array($aChoice) && $aChoice != '')
            $aChoice = array($aChoice);
        parent::__construct($aChoice);

        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
    }

    public function getForm($iDesignBoxTemplate = BX_DB_PADDING_DEF, $sTitle = false, $bOnlyForm = false)
    {
        if ($this->_sMetaType || $this->_sCategoryObject)
            return '';

        if (false === $sTitle)
            $sTitle = _t( "_Search");

        $aValues = array_merge(array('' => _t('_search_in_all_section')), $this->getKeyTitlesPairs());
        if (bx_get('type'))
            $aValue = bx_process_input(bx_get('type'));
        elseif (bx_get('section'))
            $aValue = bx_process_input(bx_get('section'));
        else
            $aValue = '';

        
        $sIdForm = $this->_sIdForm;
        $sIdResults = $this->_sIdResults;
        $sIdLoadingContainer = $sIdForm;
        $iKeywordMinLenth = 0;
        if($this->_bLiveSearch) {
            $sIdForm .= $this->_sSuffixLiveSearch;
            $sIdResults .= $this->_sSuffixLiveSearch;
            $sIdLoadingContainer = $sIdResults;
            $iKeywordMinLenth = (int)getParam('sys_search_keyword_min_len');
        }
        $sJsParams = "5, '#{$sIdForm}', '#{$sIdResults}', '#{$sIdLoadingContainer}', '{$this->_bLiveSearch}', {$iKeywordMinLenth}";

        $aForm = array(
            'form_attrs' => array(
               'id' => $sIdForm,
               'action' => BX_DOL_URL_ROOT . 'searchKeyword.php',
               'method' => 'post',
            ),
            'params' => array(
                'csrf' => array(
                    'disable' => true,
                ),
            ),
            'inputs' => array(
                'live_search' => array(
                    'type' => 'hidden',
                    'name' => 'live_search',
                    'value' => $this->_bLiveSearch ? 1 : 0,
                ),
                'section' => array(
                    'type' => 'select',
                    'name' => 'section',
                    'caption' => _t('_Section'),
                    'values' => $aValues,
                    'value' => $aValue,
                ),
                'keyword' => array(
                    'type' => 'text',
                    'name' => 'keyword',
                    'caption' => _t('_Keyword'),
                    'value' => bx_get('keyword') ? bx_process_input(bx_get('keyword')) : '',
                ),
                'search' => array(
                    'type' => 'submit',
                    'name' => 'search',
                    'value' => _t('_Search'),
                )
            )
        );

        if ($this->_bLiveSearch) {
            unset($aForm['inputs']['section']);
            unset($aForm['inputs']['search']);
            unset($aForm['inputs']['keyword']['caption']);
            $aForm['inputs']['keyword']['attrs']['placeholder'] = _t('_sys_search_placeholder');
            $aForm['inputs']['keyword']['attrs']['onkeydown'] = "return bx_search_on_type(event, $sJsParams);";
            $aForm['inputs']['keyword']['attrs']['onpaste'] = $aForm['inputs']['keyword']['attrs']['onkeydown'];
        }

        $oForm = new BxTemplFormView($aForm);
        $sForm = $oForm->getCode();

        if (!$this->_bLiveSearch) {
            $o = new BxTemplPaginate(array());
            $o->addCssJs();
        }
        if ($bOnlyForm)
            return $sForm;
        
        return '<div class="bx-page-block-container bx-def-padding-sec-topbottom bx-clearfix">' . DesignBoxContent($sTitle, $sForm, $iDesignBoxTemplate) . '</div>';
    }

    public function getResultsContainer($sCode = '')
    {
        $sIdResults = $this->_sIdResults . ($this->_bLiveSearch ? $this->_sSuffixLiveSearch : '');
        return '<div id="' . $sIdResults . '">' . $sCode . '</div>';
    }
    
    protected function getKeyTitlesPairs ()
    {
        $a = array();
        foreach ($this->aClasses as $sKey => $r)
            if ($this->_sMetaType || $r['GlobalSearch'])
                $a[$sKey] = _t($r['title']);
        return $a;
    }
}

/** @} */
