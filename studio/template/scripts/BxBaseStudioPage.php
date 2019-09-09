<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioPage extends BxDolStudioPage
{
    function __construct($mixedPageName)
    {
        parent::__construct($mixedPageName);
    }

    public function getPageIndex()
    {
        if(!is_array($this->aPage) || empty($this->aPage))
            return BX_PAGE_DEFAULT;

        if(!$this->bPageMultiple)
            return !empty($this->aPage['index']) ? (int)$this->aPage['index'] : BX_PAGE_DEFAULT;
        else
            return !empty($this->aPage[$this->sPageSelected]['index']) ? (int)$this->aPage[$this->sPageSelected]['index'] : BX_PAGE_DEFAULT;
    }

    public function getPageJs()
    {
        return array('jquery.anim.js', 'jquery.jfeed.pack.js', 'jquery.dolRSSFeed.js', 'page.js');
    }

    public function getPageJsClass()
    {
        return '';
    }

    public function getPageJsObject()
    {
        return '';
    }

    public function getPageJsCode($aOptions = array(), $bWrap = true)
    {
        $sJsClass = $this->getPageJsClass();
        $sJsObject = $this->getPageJsObject();
        if(empty($sJsClass) || empty($sJsObject))
            return '';

        $sOptions = '{}';
        if(!empty($aOptions))
            $sOptions = json_encode($aOptions);

        $sContent = 'var ' . $sJsObject . ' = new ' . $sJsClass . '(' . $sOptions . ');';
        if($bWrap)
            $sContent = BxDolStudioTemplate::getInstance()->_wrapInTagJsCode($sContent);

        return $sContent;
    }

    public function getPageCss()
    {
        $aCss = array('page.css', 'page-media-tablet.css', 'page-media-desktop.css', 'menu_top.css');
        if((int)$this->aPage['index'] == 3)
            $aCss[] = 'page_columns.css';

        return $aCss;
    }

    public function getPageHeader()
    {
        if(empty($this->aPage) || !is_array($this->aPage))
            return '';

        return _t(!$this->bPageMultiple ? $this->aPage['caption'] : $this->aPage[$this->sPageSelected]['caption']);
    }

    public function getPageBreadcrumb()
    {
        return array();
    }

    public function getPageCaption()
    {
        if(empty($this->aPage) || !is_array($this->aPage))
            return '';

        $this->updateHistory();

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oFunctions = BxTemplStudioFunctions::getInstance();

        $bActions = false;
        $sActions = $this->getPageCaptionActions();
        if(($bActions = strlen($sActions)) > 0)
            $sActions = $oFunctions->transBox('bx-std-pcap-menu-popup-actions', $sActions, true);

        $bHelp = false;
        $sHelp = $this->getPageCaptionHelp();
        if(($bHelp = strlen($sHelp)) > 0)
            $sHelp = $oFunctions->transBox('bx-std-pcap-menu-popup-help', $sHelp, true);

        $oTemplate->addInjection('injection_header', 'text', $sActions . $sHelp);

        //--- Menu Right ---//
        $aItemsRight = array();

        if($bActions)
            $aItemsRight['actions'] = array(
                'name' => 'actions',
                'icon' => 'cog',
                'onclick' => BX_DOL_STUDIO_PAGE_JS_OBJECT . ".togglePopup('actions', this)",
                'title' => '_adm_txt_show_actions'
            );

        if($bHelp)
            $aItemsRight['help'] = array(
                'name' => 'help',
                'icon' => 'question-circle',
                'onclick' => BX_DOL_STUDIO_PAGE_JS_OBJECT . ".togglePopup('help', this)",
                'title' => '_adm_txt_show_help'
            );

        $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);
        if(count($aLanguages) > 1)
            $aItemsRight['language'] = array(
                'name' => 'language',
                'icon' => 'language',
                'onclick' => "bx_menu_popup('sys_switch_language_popup', this);",
                'title' => '_adm_tmi_cpt_language'
            );

        $oTopMenu = BxTemplStudioMenuTop::getInstance();
        $oTopMenu->setSelected(BX_DOL_STUDIO_MT_LEFT, $this->aPage['name']);
        $oTopMenu->setContent(BX_DOL_STUDIO_MT_CENTER, _t($this->aPage['caption']));
        $oTopMenu->setContent(BX_DOL_STUDIO_MT_RIGHT, array('template' => 'menu_top_toolbar.html', 'menu_items' => $aItemsRight));

        return '';
    }

    public function getPageAttributes()
    {
        return '';
    }

    public function getPageMenu($aMenu, $aMarkers = array())
    {
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_side.html', 'menu_items' => $aMenu));
        if(!empty($aMarkers))
            $oMenu->addMarkers($aMarkers);

        return $oMenu->getCode();
    }

    public function getPageCode($bHidden = false) {}

    protected function getPageCaptionHelp()
    {
    	$sContent = BxDolRss::getObjectInstance($this->sPageRssHelpObject)->getHolder($this->sPageRssHelpId, $this->iPageRssHelpLength, 0, false);

        $oTemplate = BxDolStudioTemplate::getInstance();
    	$oTemplate->addJsTranslation('_adm_txt_show_help_content_empty');
        return $oTemplate->parseHtmlByName('page_caption_help.html', array(
            'content' => $sContent
        ));
    }

    protected function getPageCaptionActions()
    {
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

        $oForm = new BxTemplStudioFormView($aForm);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('page_caption_actions.html', array('content' => $oForm->getCode()));
    }

    /**
     *
     * Block Methods
     *
     */
    public function getBlocksLine($aBlocks)
    {
        $aTmplVarsBlocks = array();
        foreach ($aBlocks as $aBlock) {
            $aTmplVarsBlocks[] = array(
                'content' => $this->getBlockCode($aBlock)
            ); 
        }

    	return BxDolStudioTemplate::getInstance()->parseHtmlByName('page_blocks_line.html', array(
    	    'count' => count($aTmplVarsBlocks),
    		'bx_repeat:blocks' => $aTmplVarsBlocks
    	));
    }
    public function getBlockCode($aBlock)
    {
    	return BxDolStudioTemplate::getInstance()->parseHtmlByName('page_block.html', array(
    		'caption' => $this->getBlockCaption($aBlock),
    		'panel_top' => $this->getBlockPanelTop($aBlock),
    		'items' => !empty($aBlock['items']) ? $aBlock['items'] : '',
    		'panel_bottom' => $this->getBlockPanelBottom($aBlock)
    	));
    }

    public function getBlockCaption($aBlock)
    {
        if(empty($aBlock) || !is_array($aBlock) || (empty($aBlock['caption']) && empty($aBlock['actions'])))
            return '';

        $aTmplActions = array();
        if(!empty($aBlock['actions']) && is_array($aBlock['actions']))
            foreach($aBlock['actions'] as $aAction) {
                $sCaption = is_array($aAction['caption']) ? call_user_func_array('_t', $aAction['caption']) : _t($aAction['caption']);

                $bOnClick = !empty($aAction['onclick']);
                $aOnClick = $bOnClick ? array('onclick' => $aAction['onclick']) : array();

                $aTmplActions[] = array(
                    'name' => $aAction['name'],
                    'url' => $aAction['url'],
                    'title' => $sCaption,
                    'bx_if:show_onclick' => array(
                        'condition' => $bOnClick,
                        'content' => $aOnClick
                    ),
                    'caption' => $sCaption
                );
            }

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('block_caption.html', array(
            'caption' => is_array($aBlock['caption']) ? call_user_func_array('_t', $aBlock['caption']) : _t($aBlock['caption']),
            'bx_if:show_actions' => array(
                'condition' => !empty($aTmplActions),
                'content' => array(
                    'bx_repeat:actions' => $aTmplActions
                )
            ),
        ));
    }

    public function getBlockPanelTop($aBlock)
    {
        if(empty($aBlock) || !is_array($aBlock) || empty($aBlock['panel_top']))
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('block_panel_top.html', array(
            'content' => $aBlock['panel_top']
        ));
    }

    public function getBlockPanelBottom($aBlock)
    {
        if(empty($aBlock) || !is_array($aBlock) || empty($aBlock['panel_bottom']))
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('block_panel_bottom.html', array(
            'content' => $aBlock['panel_bottom']
        ));
    }

    protected function getJsResult($sMessage, $bTranslate = true, $bRedirect = false, $sRedirect = '', $sOnResult = '')
    {
        return $this->getJsResultBy(array(
            'message' => $sMessage,
            'translate' => $bTranslate,
            'redirect' => $bRedirect === true && !empty($sRedirect) ? $sRedirect : $bRedirect,
            'eval' => $sOnResult
        ));
    }

    protected function getJsResultBy($aParams)
    {
        $aResult = array();

        if(!empty($aParams['message'])) {
            $aResult['message'] = $aParams['message'];

            if(!isset($aParams['translate']) || !empty($aParams['translate'])) {
                $aTrtParams = array($aResult['message']);
                if(!empty($aParams['translate']) && is_array($aParams['translate']))
                    $aTrtParams = array_merge($aTrtParams, $aParams['translate']);

                $aResult['message'] = call_user_func_array ('_t', $aTrtParams);
            }
        }

        if(isset($aParams['redirect']) && $aParams['redirect'] !== false)
            $aResult['redirect'] = is_string($aParams['redirect']) ? $aParams['redirect'] : BX_DOL_URL_STUDIO;

        if(!empty($aParams['eval']))
            $aResult['eval'] = $aParams['eval'];

        $sResult = "window.parent.processJsonData(" . json_encode($aResult) . ");";
        if(isset($aParams['on_page_load']) && $aParams['on_page_load'] === true)
            $sResult = "$(document).ready(function() {" . $sResult . "});";

        return BxDolStudioTemplate::getInstance()->_wrapInTagJsCode($sResult);
    }
}

/** @} */
