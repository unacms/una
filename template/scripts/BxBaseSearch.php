<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolSearch');

class BxBaseSearch extends BxDolSearch
{
    protected $_oTemplate;
    protected $_sIdForm = 'sys_search_form';
    protected $_sIdResults = 'sys_search_results';
    protected $_sSuffixQuickSearch = '_quick';

    public function __construct($aChoice, $oTemplate)
    {
        parent::__construct($aChoice);

        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
    }

    public function getForm($iDesignBoxTemplate = BX_DB_PADDING_DEF, $sTitle = false)
    {
        if (false === $sTitle)
            $sTitle = _t( "_Search");

        $aValues = $this->getKeyTitlesPairs ();        
        $aValue = isset($_GET['type']) ? bx_process_input($_GET['type']) : array_keys($aValues);

        $sIdForm = $this->_sIdForm . ($this->_bQuickSearch ? $this->_sSuffixQuickSearch : '');
        $sIdResults = $this->_sIdResults . ($this->_bQuickSearch ? $this->_sSuffixQuickSearch : '');
        $sIdLoadingContainer = $this->_bQuickSearch ? $sIdResults : $sIdForm;

        $aForm = array(
            'form_attrs' => array(
               'id' => $sIdForm,
               'action' => '',
               'method' => 'post',
               'onsubmit' => "return bx_search('#{$sIdForm}', '#{$sIdResults}', '#{$sIdLoadingContainer}');",
            ),
            'inputs' => array(
                'quick_search' => array(
                    'type' => 'hidden',
                    'name' => 'quick_search',
                    'value' => $this->_bQuickSearch ? 1 : 0,
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
                ),
                'search' => array(
                    'type' => 'submit',
                    'name' => 'search',
                    'value' => _t('_Search'),
                )
            )
        );

        if ($this->_bQuickSearch) {
            unset($aForm['inputs']['section']);
            unset($aForm['inputs']['search']);
            unset($aForm['inputs']['keyword']['caption']);
        }

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView($aForm);
        $sForm = $oForm->getCode();

        if (!$this->_bQuickSearch) {
            bx_import('BxTemplPaginate');
            $o = new BxTemplPaginate(array());
            $o->addCssJs();
        }

        return DesignBoxContent($sTitle, $sForm, $iDesignBoxTemplate);
    }

    public function getResultsContainer($sCode = '')
    {
        $sIdResults = $this->_sIdResults . ($this->_bQuickSearch ? $this->_sSuffixQuickSearch : '');
        return '<div id="' . $sIdResults . '" class="bx-def-margin-top">' . $sCode . '</div>';
    }
}

/** @} */
