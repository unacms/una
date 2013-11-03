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

bx_import('BxDolStudioPage');
bx_import('BxDolStudioTemplate');
bx_import('BxTemplStudioFunctions');

require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

class BxBaseStudioPage extends BxDolStudioPage {

    function BxBaseStudioPage($mixedPageName) {
        parent::BxDolStudioPage($mixedPageName);
    }

    function getPageIndex() {
        if(!is_array($this->aPage) || empty($this->aPage))
            return BX_PAGE_DEFAULT;

        if(!$this->bPageMultiple)
            return !empty($this->aPage['index']) ? (int)$this->aPage['index'] : BX_PAGE_DEFAULT;
        else
            return !empty($this->aPage[$this->sPageSelected]['index']) ? (int)$this->aPage[$this->sPageSelected]['index'] : BX_PAGE_DEFAULT;
    }

    function getPageJs() {
        return array('common_anim.js', 'page.js');
    }

    function getPageJsClass() {
        return '';
    }

	function getPageJsObject() {
        return '';
    }

	function getPageJsCode($aOptions = array(), $bWrap = true) {
        $sJsClass = $this->getPageJsClass();
        $sJsObject = $this->getPageJsObject();
        if(empty($sJsClass) || empty($sJsObject))
        	return '';

		$sOptions = '{}';
		if(!empty($aOptions)) {
			$oJson = new Services_JSON();		        
			$sOptions = $oJson->encode($aOptions);
		}

        $sContent = 'var ' . $sJsObject . ' = new ' . $sJsClass . '(' . $sOptions . ');';
		if($bWrap)
        	$sContent = BxDolStudioTemplate::getInstance()->_wrapInTagJsCode($sContent);

        return $sContent;
    }

    function getPageCss() {
        $aCss = array('menu_top.css');
        if((int)$this->aPage['index'] == 3)
            $aCss[] = 'page_columns.css';

        return $aCss;
    }

    function getPageHeader() {
        if(empty($this->aPage) || !is_array($this->aPage))
            return '';

        return _t(!$this->bPageMultiple ? $this->aPage['caption'] : $this->aPage[$this->sPageSelected]['caption']);
    }

    function getPageBreadcrumb() {
        return array();
    }

    function getPageCaption() {
        if(empty($this->aPage) || !is_array($this->aPage))
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oFunctions = BxTemplStudioFunctions::getInstance();

        $sHelp = $this->getPageCaptionHelp();
        if(($bHelp = strlen($sHelp)) > 0) {
            $sHelp = $oFunctions->transBox($sHelp);
            $sHelp = $oTemplate->parseHtmlByName('page_caption_help_popup.html', array('content' => $sHelp));
        }

        $sActions = $this->getPageCaptionActions();
        if(($bActions = strlen($sActions)) > 0) {
            $sActions = $oFunctions->transBox($sActions);
            $sActions = $oTemplate->parseHtmlByName('page_caption_actions_popup.html', array('content' => $sActions));
        }

        $oTemplate->addInjection('injection_header', 'text', $sHelp . $sActions);

        //--- Menu Left ---//
        $aItems = array(
        	'back' => array(
                'name' => 'back',
                'icon' => 'th',
                'link' => BX_DOL_URL_STUDIO . 'launcher.php',
                'title' => '_adm_txt_back_to_launcher'
            )
        );

        bx_import('BxTemplStudioMenu');
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_top_toolbar.html', 'menu_items' => $aItems));
        $sMenuLeft = $oMenu->getCode();

        //--- Menu Right ---//
        $aItems = array();
        if($bHelp)
            $aItems['help'] = array(
                'name' => 'help',
                'icon' => 'question-circle',
                'onclick' => BX_DOL_STUDIO_PAGE_JS_OBJECT . ".togglePopup('help', this)",
                'title' => '_adm_txt_show_help'
            );
        if($bActions)
            $aItems['actions'] = array(
                'name' => 'actions',
                'icon' => 'cog',
                'onclick' => BX_DOL_STUDIO_PAGE_JS_OBJECT . ".togglePopup('actions', this)",
                'title' => '_adm_txt_show_help'
            );
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_top_toolbar.html', 'menu_items' => $aItems));
        $sMenuRight = $oMenu->getCode();
        
        $aTmplVars = array(
            'menu_left' => $sMenuLeft,
            'caption' => _t($this->aPage['caption']),
        	'menu_right' => $sMenuRight
        );
        return $oTemplate->parseHtmlByName('page_caption.html', $aTmplVars);
    }

    function getPageAttributes() {
        return '';
    }

    function getPageMenu($aMenu, $aMarkers = array()) {
        bx_import('BxTemplStudioMenu');
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_side.html', 'menu_items' => $aMenu));
        if(!empty($aMarkers))
        	$oMenu->addMarkers($aMarkers);

        return $oMenu->getCode();
    }

    function getPageCode($bHidden = false) {}

    protected function getPageCaptionHelp() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sContent = _t('_adm_txt_show_help_content_empty');
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }

    protected function getPageCaptionActions() {
        if(empty($this->aActions))
            return "";

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-page-actions',
                'name' => 'adm-page-actions',
                'action' => '',
                'method' => 'post',
            ),
            'params' => array(),
            'inputs' => array()
        );

        foreach($this->aActions as $aAction) {
            $aInput = array(
                'type' => $aAction['type'],
                'name' => $aAction['name'],
                'caption' => _t($aAction['caption'])
            );

            switch($aAction['type']) {
                case 'switcher':
                    $aInput['checked'] = $aAction['checked'];
                    $aInput['attrs']['onchange'] = $aAction['onchange'];
                    break;
            
            }

            $aForm['inputs'][$aInput['name']] = $aInput;
        }

        bx_import('BxTemplStudioFormView');
        $oForm = new BxTemplStudioFormView($aForm);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('page_caption_actions.html', array('content' => $oForm->getCode()));
    }

    /**
     * 
     * Block Methods
     * 
     */ 
    function getBlockCaption($aBlock) {
        if(empty($aBlock) || !is_array($aBlock))
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplActions = array();
        if(isset($aBlock['actions']) && is_array($aBlock['actions']))
            foreach($aBlock['actions'] as $aAction) {
                $sCaption = _t($aAction['caption']);

                $aTmplActions[] = array(
                    'name' => $aAction['name'],
                    'url' => $aAction['url'],
                    'title' => $sCaption,
                    'bx_if:show_onclick' => array(
                        'condition' => $aAction['onclick'] != '',
                        'content' => array(
                            'onclick' => $aAction['onclick']
                        )
                    ),
                    'caption' => $sCaption
                );
            }

        $aTmplVars = array(
            'caption' => _t($aBlock['caption']),
            'bx_if:show_actions' => array(
                'condition' => count($aTmplActions) > 0,
                'content' => array(
                    'bx_repeat:actions' => $aTmplActions
                )
            ),
        );
        return $oTemplate->parseHtmlByName('block_caption.html', $aTmplVars);
    }

    function getBlockPanelTop($aBlock) {
        if(empty($aBlock) || !is_array($aBlock))
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
            'content' => isset($aBlock['panel_top']) ? $aBlock['panel_top'] : ''
        );
        return $oTemplate->parseHtmlByName('block_panel_top.html', $aTmplVars);
    }

    function getBlockPanelBottom($aBlock) {
        if(empty($aBlock) || !is_array($aBlock))
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
            'content' => isset($aBlock['panel_bottom']) ? $aBlock['panel_bottom'] : ''
        );
        return $oTemplate->parseHtmlByName('block_panel_bottom.html', $aTmplVars);
    }

    protected function getJsResult($sMessage, $bTranslate = true, $bRedirect = false, $sRedirect = '', $sOnResult = '') {
        $aResult = array();
        $aResult['message'] = $bTranslate ? _t($sMessage) : $sMessage;
        if($bRedirect)
            $aResult['redirect'] = $sRedirect != '' ? $sRedirect : BX_DOL_URL_STUDIO;

        if(!empty($sOnResult))
            $aResult['on_result'] = $sOnResult;

        $oJson = new Services_JSON();
        $sResult = "window.parent." . BX_DOL_STUDIO_PAGE_JS_OBJECT . ".showMessage(" . $oJson->encode($aResult) . ");";

        return BxDolStudioTemplate::getInstance()->_wrapInTagJsCode($sResult);
    }
}
/** @} */
