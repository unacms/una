<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxShopifyTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_shopify';
        parent::__construct($oConfig, $oDb);
    }

    public function getIncludeCssJs()
    {
        $this->addCss(array(
            'unit.css'
        ));

        $sProto = bx_proto();
        $this->addJs(array(
            $sProto . '://sdks.shopifycdn.com/js-buy-sdk/v0/latest/shopify-buy.umd.polyfilled.min.js',
            'shop.js'
        ));
    }

    public function getIncludeCode($iProfileId, $aSettings)
    {
        if(empty($aSettings))
            return '';

        $sCode = $this->getJsCode('shop', array(
            'sAPIKey' => $aSettings['api_key'],
            'sDomain' => $aSettings['domain'],
            'sAppId' => $aSettings['app_id'],
        ));

        $this->addJsTranslation(array(
            '_bx_shopify_err_load_product',
            '_bx_shopify_err_load_collection'
        ));

        return bx_replace_markers($sCode, array(
            'profile_id' => $iProfileId
        ));
    }

    public function entryBuy($aData)
    {
        return $this->parseHtmlByName('entry-buy.html', array(
        	'html_id' => $this->_oConfig->getHtmlIds('entry_buy')
        ));
    }

    public function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        return $this->parseHtmlByName($sTemplateName, array(
            'js_object' => $this->_oConfig->getJsObjectShop($aData[$CNF['FIELD_AUTHOR']]),
            'html_id' => $this->_oConfig->getHtmlIds('entry_content'),
            'entry_title' => $this->getTitle($aData),
            'entry_code' => $aData[$CNF['FIELD_CODE']]
        ));
    }

    public function entryAttachments ($aData, $aParams = array())
    {
        $sPopupId = 'bx-messages-atachment-popup-{id}';
        return $this->parseHtmlByName('attachments.html', array(
            'html_id' => $this->_oConfig->getHtmlIds('entry_attachments'),
            'html_id_sample' => $this->_oConfig->getHtmlIds('entry_attachment_sample'),
        	'popup_id' => $sPopupId,
            'popup' => BxTemplFunctions::getInstance()->transBox($sPopupId, '<img class="bx-spf-attachment-popup-img" src="" />', true, true),
        ));
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    protected function getUnit($aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $iProfileId = $aData[$CNF['FIELD_AUTHOR']];
        $sCode = $aData[$CNF['FIELD_CODE']] . $this->_getUnitClass($aData,(isset($aParams['template_name']) ? $aParams['template_name'] : ''));
        return array_merge(parent::getUnit($aData, $aParams), array(
            'js_object' => $this->_oConfig->getJsObjectShop($iProfileId),
            'js_content' => $this->_oModule->serviceIncludeCode($iProfileId),
        	'class' => $this->_oConfig->getHtmlIds('unit') . $sCode,
            'entry_code' => $sCode,
        ));
    }
}

/** @} */
