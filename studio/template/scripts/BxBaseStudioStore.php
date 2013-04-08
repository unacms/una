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

bx_import('BxDolStudioStore');
bx_import('BxTemplStudioFunctions');

class BxBaseStudioStore extends BxDolStudioStore {
    function BxBaseStudioStore($sPage = "") {
        parent::BxDolStudioStore($sPage);
    }

    function getPageCss() {
        return array_merge(parent::getPageCss(), array('store.css'));
    }

    function getPageJs() {
        return array_merge(parent::getPageJs(), array('store.js'));
    }

    function getPageJsObject() {
        return 'oBxDolStudioStore';
    }

    function getPageMenu() {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array('goodies', 'featured', 'purchases', 'updates', 'checkout');
        foreach($aMenuItems as $sMenuItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => 'mi-str-' . $sMenuItem . '.png',
            	'link' => BX_DOL_URL_STUDIO . 'store.php?page=' . $sMenuItem,
            	'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
            	'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode() {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    protected function getGoodies() {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aProducts = $this->loadGoodies();

        $TmplVars = array(
			'js_object' => $sJsObject,
            'bx_repeat:blocks' => array()
        );

        $sActions = "";
        foreach($aProducts as $aBlock) {
            $sItems = "";
            foreach($aBlock['items'] as $aProduct)
                $sItems .= $oTemplate->parseHtmlByName('product.html', array());

            $TmplVars['bx_repeat:blocks'][] = array(
            	'caption' => $this->getBlockCaption($aBlock),
                'items' => $sItems
            );
        }

        return $oTemplate->parseHtmlByName('store.html', $TmplVars);
    }

    protected function getPurchases() {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aProducts = $this->loadPurchases();

        $sItems = "";
        foreach($aProducts as $aProduct) {
            $sIcon = $oTemplate->getIconUrl($aProduct['name'] . '@modules/' . $aProduct['dir'] . '|std-si.png');
            if(empty($sIcon))
                $sIcon = $oTemplate->getIconUrl('pi-str-dump.png');

            $sItems .= $oTemplate->parseHtmlByName('product.html', array(
            	'js_object' => $sJsObject,
                'url' => $aProduct['link_market'],
                'icon' => $sIcon,
                'title' => $aProduct['title'],
                'vendor' => $aProduct['vendor'],
            	'dir' => $aProduct['dir'],
                'bx_if:hide_install' => array(
                    'condition' => $aProduct['installed'],
                    'content' => array()
                ),
                'bx_if:hide_installed' => array(
                    'condition' => !$aProduct['installed'],
                    'content' => array()
                )
            ));
        }

        $TmplVars = array(
			'js_object' => $sJsObject,
            'bx_repeat:blocks' => array(
                array(
                	'caption' => $this->getBlockCaption(array('caption' => _t('_adm_block_cpt_purchases'), 'actions' => array())),
                    'items' => $sItems
                )
            )
        );

        return $oTemplate->parseHtmlByName('store.html', $TmplVars);
    }
}
/** @} */