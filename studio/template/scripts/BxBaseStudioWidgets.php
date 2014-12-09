<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

bx_import('BxDolStudioWidgets');

class BxBaseStudioWidgets extends BxDolStudioWidgets
{
    public function __construct($mixedPageName)
    {
        parent::__construct($mixedPageName);
    }

    public function getPageCode($bHidden = false)
    {
        if(empty($this->aPage) || !is_array($this->aPage))
            return BxDolStudioTemplate::getInstance()->displayPageNotFound();

        if(!$this->bPageMultiple)
            return $this->wrapWidgets($this->aPage['name'], $this->getWidgets($this->aPage['name'], $this->aWidgets), $bHidden);

        $sContent = "";
        foreach($this->aWidgets as $sPage => $aWidgets)
            $sContent .= $this->wrapWidgets($sPage, $this->getWidgets($sPage, $aWidgets), $bHidden || $sPage != $this->sPageSelected);

        return $sContent;
    }

    public function getPageWidgets()
    {
        if(empty($this->aPage) || !is_array($this->aPage))
            return BxDolStudioTemplate::getInstance()->displayPageNotFound();

        if(!$this->bPageMultiple)
            return $this->getWidgets($this->aPage['name'], $this->aWidgets);

        $sContent = "";
        foreach($this->aWidgets as $sPage => $aWidgets)
            $sContent .= $this->getWidgets($sPage, $aWidgets);

        return $sContent;
    }

    protected function wrapWidgets($sName, $sContent, $bHidden = false)
    {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('widgets_page.html', array(
                'page' => $sName,
                'bx_if:page_hidden' => array(
                    'condition' => $bHidden,
                    'content' => array()
                ),
                'content' => $sContent
            ));
    }

    protected function getWidgets($sPage, $aWidgets)
    {
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

                foreach($aActions as $aAction) {
                	$sActionIcon = $aAction['icon'];
                	$bActionIcon = strpos($sActionIcon, '.') === false;

                	if(!$bActionIcon)
						$sActionIcon = $oTemplate->getIconUrl($sActionIcon);

                	$sCaption = _t($aAction['caption']);
                    $aTmplVarsActions[] = array(
                        'caption' => $sCaption,
                        'url' => !empty($aAction['url']) ? $oTemplate->parseHtmlByContent($aAction['url'], $aParseVars, array('{', '}')) : 'javascript:void(0)',
                        'bx_if:show_click' => array(
                            'condition' => !empty($aAction['click']),
                            'content' => array(
                                'content' => 'javascript:' . $aAction['click'],
                            )
                        ),
                        'bx_if:action_icon' => array (
			                'condition' => $bActionIcon,
			                'content' => array('icon' => $sActionIcon, 'caption' => $sCaption),
			            ),
		                'bx_if:action_image' => array (
			                'condition' => !$bActionIcon,
			                'content' => array('icon_url' => $sActionIcon, 'caption' => $sCaption),
			            ),
                    );
                }
            }

            $sIcon = $this->getIcon($aWidget);
            $bIcon = strpos($sIcon, '.') === false;

            $sNotices = !empty($this->aWidgetsNotices[$aWidget['id']]) ? $this->aWidgetsNotices[$aWidget['id']] : '';

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
                    'condition' => !empty($sNotices),
                    'content' => array(
                        'content' => $sNotices
                    )
                ),
                'bx_if:show_actions' => array(
                    'condition' => !empty($aTmplVarsActions),
                    'content' => array(
                        'bx_repeat:actions' => $aTmplVarsActions,
                    )
                ),
	            'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
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

    protected function getIcon(&$aWidget)
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sUrl = $oTemplate->getIconUrl($aWidget['icon']);
        if(empty($sUrl)) {
        	bx_import('BxDolModuleQuery');
	        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($aWidget['module']);

        	bx_import('BxDolStudioUtils');
            $sUrl = BxDolStudioUtils::getIconDefault($aModule['type']);
        }

        return $sUrl;
    }
}

/** @} */
