<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioPolyglot');

class BxBaseStudioPolyglot extends BxDolStudioPolyglot {
    function BxBaseStudioPolyglot($sPage = '') {
        parent::BxDolStudioPolyglot($sPage);
    }
    function getPageCss() {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'polyglot.css'));
    }
    function getPageJs() {
        return array_merge(parent::getPageJs(), array('jquery.autoresize.js', 'settings.js', 'polyglot.js'));
    }
    function getPageJsObject() {
        return 'oBxDolStudioPolyglot';
    }
    function getPageMenu($aMenu = array(), $aMarkers = array()) {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_PGT_TYPE_GENERAL, 
            BX_DOL_STUDIO_PGT_TYPE_KEYS, 
            BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES
        );
        foreach($aMenuItems as $sMenuItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => 'mi-pgt-' . $sMenuItem . '.png',
            	'link' => BX_DOL_URL_STUDIO . 'polyglot.php?page=' . $sMenuItem,
            	'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
            	'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode($bHidden = false) {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    protected function getGeneral() {
        $oTemplate = BxDolStudioTemplate::getInstance();
        
        bx_import('BxTemplStudioSettings');
        $oPage = new BxTemplStudioSettings(BX_DOL_STUDIO_STG_TYPE_DEFAULT, BX_DOL_STUDIO_STG_CATEGORY_LANGUAGES);

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
        	'bx_repeat:blocks' => $oPage->getPageCode(),
            'bx_if:show_new_key_popup' => array(
                'condition' => false,
                'content' => array()
            )
        );

        return $oTemplate->parseHtmlByName('polyglot.html', $aTmplVars);
    }

    protected function getKeys() {
        return $this->getGrid('sys_studio_lang_keys');
    }

    protected function getEtemplates() {
        return $this->getGrid('sys_studio_lang_etemplates');
    }

    protected function getGrid($sObjectName) {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
        	'bx_repeat:blocks' => array(
                array(
                	'caption' => '',
                    'panel_top' => '',
                    'items' => $oGrid->getCode(),
                    'panel_bottom' => ''
                )
            )
        );

        return $oTemplate->parseHtmlByName('polyglot.html', $aTmplVars);
    }
}
/** @} */
