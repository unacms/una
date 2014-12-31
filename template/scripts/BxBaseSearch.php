<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolSearch');

class BxBaseSearch extends BxDolSearch
{
    protected $_oTemplate;
    protected $_sIdForm = 'sys_search_form';
    protected $_sIdResults = 'sys_search_results';
    protected $_sSuffixLiveSearch = '_quick';

    public function __construct($aChoice, $oTemplate)
    {
        parent::__construct($aChoice);

        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
    }

    public function getForm($iDesignBoxTemplate = BX_DB_PADDING_DEF, $sTitle = false)
    {
        if ($this->_sMetaType)
            return '';

        if (false === $sTitle)
            $sTitle = _t( "_Search");

        $aValues = $this->getKeyTitlesPairs ();
        if (bx_get('type'))
            $aValue = bx_process_input(bx_get('type'));
        elseif (bx_get('section'))
            $aValue = bx_process_input(bx_get('section'));
        else
            $aValue = array_keys($aValues);

        $sIdForm = $this->_sIdForm . ($this->_bLiveSearch ? $this->_sSuffixLiveSearch : '');
        $sIdResults = $this->_sIdResults . ($this->_bLiveSearch ? $this->_sSuffixLiveSearch : '');
        $sIdLoadingContainer = $this->_bLiveSearch ? $sIdResults : $sIdForm;
        $sJsParams = "5, '#{$sIdForm}', '#{$sIdResults}', '#{$sIdLoadingContainer}', '{$this->_bLiveSearch}'";

        $aForm = array(
            'form_attrs' => array(
               'id' => $sIdForm,
               'action' => BX_DOL_URL_ROOT . 'searchKeyword.php',
               'method' => 'post',
            ),
            'csrf' => array(
                'disable' => true,
            ),
            'inputs' => array(
                'live_search' => array(
                    'type' => 'hidden',
                    'name' => 'live_search',
                    'value' => $this->_bLiveSearch ? 1 : 0,
                ),
                'section' => array(
                    'type' => 'checkbox_set',
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
            $aForm['inputs']['keyword']['attrs']['onkeypress'] = "return bx_search_on_type(event, $sJsParams);";
            $aForm['inputs']['keyword']['attrs']['onpaste'] = $aForm['inputs']['keyword']['attrs']['onkeypress'];
        }

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($aForm);
        $sForm = $oForm->getCode();

        if (!$this->_bLiveSearch) {
            bx_import('BxTemplPaginate');
            $o = new BxTemplPaginate(array());
            $o->addCssJs();
        }

        return '<div class="bx-page-block-container bx-def-padding-topbottom bx-clearfix">' . DesignBoxContent($sTitle, $sForm, $iDesignBoxTemplate) . '</div>';
    }

    public function getResultsContainer($sCode = '')
    {
        $sIdResults = $this->_sIdResults . ($this->_bLiveSearch ? $this->_sSuffixLiveSearch : '');
        return '<div id="' . $sIdResults . '">' . $sCode . '</div>';
    }
}

/** @} */
