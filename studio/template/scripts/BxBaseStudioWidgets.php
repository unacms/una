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

bx_import('BxDolStudioWidgets');

class BxBaseStudioWidgets extends BxDolStudioWidgets {

    function __construct($mixedPageName) {
        parent::__construct($mixedPageName);
    }

    function getPageCode($bHidden = false) {
        if(empty($this->aPage) || !is_array($this->aPage))
            return BxDolStudioTemplate::getInstance()->displayPageNotFound();

        if(!$this->bPageMultiple)
            return $this->wrapWidgets($this->aPage['name'], $this->getWidgets($this->aPage['name'], $this->aWidgets), $bHidden);

        $sContent = "";
        foreach($this->aWidgets as $sPage => $aWidgets)
            $sContent .= $this->wrapWidgets($sPage, $this->getWidgets($sPage, $aWidgets), $bHidden || $sPage != $this->sPageSelected);

        return $sContent;
    }

    function getPageWidgets() {
        if(empty($this->aPage) || !is_array($this->aPage))
            return BxDolStudioTemplate::getInstance()->displayPageNotFound();

        if(!$this->bPageMultiple)
            return $this->getWidgets($this->aPage['name'], $this->aWidgets);

        $sContent = "";
        foreach($this->aWidgets as $sPage => $aWidgets)
            $sContent .= $this->getWidgets($sPage, $aWidgets);

        return $sContent;
    }

    protected function wrapWidgets($sName, $sContent, $bHidden = false) {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('widgets_page.html', array(
                'page' => $sName,
                'bx_if:page_hidden' => array(
                    'condition' => $bHidden,
                    'content' => array()
                ),
            	'content' => $sContent
            ));
    }

    protected function getWidgets($sPage, $aWidgets) {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aParseVars = array(
            'url_root' => BX_DOL_URL_ROOT,
            'url_studio' => BX_DOL_URL_STUDIO,
            'url_studio_icons' => BX_DOL_URL_STUDIO_BASE . 'images/icons/'
        );

        $aTmplVars = array();
        foreach($aWidgets as $aWidget) {
            $aTmplVarsActions = array();
            if(!$this->isEnabled($aWidget) && !empty($aWidget['cnt_actions'])) {
                $aService = unserialize($aWidget['cnt_actions']);
                $aActions = BxDolService::call($aService['module'], $aService['method'], array_merge(array($aWidget), $aService['params']), $aService['class']);

                foreach($aActions as $aAction)
                    $aTmplVarsActions[] = array(
                        'caption' => _t($aAction['caption']),
                        'url' => !empty($aAction['url']) ? $oTemplate->parseHtmlByContent($aAction['url'], $aParseVars, array('{', '}')) : 'javascript:void(0)',
                        'bx_if:show_click' => array(
                            'condition' => !empty($aAction['click']),
                            'content' => array(
                                'content' => 'javascript:' . $aAction['click'],
                            )
                        ),
    					'icon' => $oTemplate->getIconUrl($aAction['icon'])
                    );
            }

            $aTmplVars[] = array(
                'id' => $aWidget['id'],
                'url' => !empty($aWidget['url']) ? $oTemplate->parseHtmlByContent($aWidget['url'], $aParseVars, array('{', '}')) : 'javascript:void(0)',
                'bx_if:show_click_icon' => array(
                    'condition' => !empty($aWidget['click']),
                    'content' => array(
                        'content' => 'javascript:' . $aWidget['click'],
                    )
                ),
                'bx_if:show_click_link' => array(
                    'condition' => !empty($aWidget['click']),
                    'content' => array(
                        'content' => 'javascript:' . $aWidget['click'],
                    )
                ),
                'bx_if:show_notice' => array(
                    'condition' => !empty($aWidget['cnt_notices']),
                    'content' => array(
                        'content' => $aWidget['cnt_notices']
                    )
                ),
                'bx_if:show_actions' => array(
                    'condition' => !empty($aTmplVarsActions),
                    'content' => array(
                        'bx_repeat:actions' => $aTmplVarsActions,
                    )
                ),
                'icon' => $this->getIcon($aWidget),
                'caption' => _t($aWidget['caption']),
                'widget_disabled_class' => !$this->isEnabled($aWidget) ? 'bx-std-widget-icon-disabled' : '',
                'widget_bookmarked_class' => (int)$aWidget['bookmark'] == 1 ? 'bx-std-widget-icon-bookmarked' : '',
                'widget_styles' => isset($_COOKIE['bx_studio_bookmark']) && (int)$_COOKIE['bx_studio_bookmark'] == 1 && (int)$aWidget['bookmark'] != 1 ? 'display:none;' : ''
            );
        }

        return $oTemplate->parseHtmlByName('widgets.html', array(
            'bx_repeat:widgets' => $aTmplVars
        ));
    }

    protected function getIcon(&$aWidget) {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sUrl = $oTemplate->getIconUrl($aWidget['icon']);
        if(empty($sUrl))
            $sUrl = $oTemplate->getIconUrl('wi-empty.png');

        return $sUrl;
    }
}
/** @} */
