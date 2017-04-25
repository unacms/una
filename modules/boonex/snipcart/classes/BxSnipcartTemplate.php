<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxSnipcartTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_snipcart';
        parent::__construct($oConfig, $oDb);
    }

    public function getSctInclude($iProfileId)
    {
        $aSettings = $this->getModule()->getSettings($iProfileId);
        if(empty($aSettings))
            return '';

        $sKey = $this->_oConfig->getApiKey($aSettings);
        if(empty($sKey))
            return '';

        return $this->parseHtmlByName('sct_include.html', array(
            'key' => $sKey
        ));
    }

    public function getSctButton($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $aSettings = $this->getModule()->getSettings($aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(empty($aSettings))
            return '';

        $aCurrency = $this->_oConfig->getCurrency($aSettings);
        return $this->parseHtmlByName('sct_button.html', array(
            'bx_repeat:attributes' => array(
                array('key' => 'id', 'value' => $aContentInfo[$CNF['FIELD_ID']]),
                array('key' => 'name', 'value' => bx_html_attribute($aContentInfo[$CNF['FIELD_TITLE']])),
                array('key' => 'price', 'value' => sprintf('%01.2f', $aContentInfo[$CNF['FIELD_PRICE']])),
                array('key' => 'weight', 'value' => $aContentInfo[$CNF['FIELD_WEIGHT']]),
                array('key' => 'url', 'value' => $this->_oConfig->getViewUrl($aContentInfo[$CNF['FIELD_ID']])),
                array('key' => 'description', 'value' => bx_html_attribute(strip_tags($aContentInfo[$CNF['FIELD_TEXT']]))),
            ),
            'content' => _t('_bx_snipcart_menu_item_title_buy_for', $aContentInfo[$CNF['FIELD_PRICE']], $aCurrency['sign'], $aCurrency['code'])
        ));
    }

    public function getBuyButton($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $aSettings = $this->getModule()->getSettings($aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(empty($aSettings))
            return '';

        $aCurrency = $this->_oConfig->getCurrency($aSettings);
        $sContent = _t('_bx_snipcart_menu_item_title_buy_for', $aContentInfo[$CNF['FIELD_PRICE']], $aCurrency['sign'], $aCurrency['code']);

        $this->addCss(array('timeline.css'));
        return $this->parseHtmlByName('buy_link.html', array(
            'href' => $this->_oConfig->getViewUrl($aContentInfo[$CNF['FIELD_ID']]),
            'title' => bx_html_attribute($sContent),
            'content' => $sContent
        ));
    }

    protected function getUnit($aData, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aSettings = $this->getModule()->getSettings($aData[$CNF['FIELD_AUTHOR']]);
        $bSettings = !empty($aSettings);

        $sBuyTitle = '';
        if($bSettings) {
            $aCurrency = $this->_oConfig->getCurrency($aSettings);
            $sBuyTitle = _t('_bx_snipcart_menu_item_title_buy_for', $aData[$CNF['FIELD_PRICE']], $aCurrency['sign'], $aCurrency['code']);
        }

        $aTmplVars = parent::getUnit($aData, $aParams);
        $aTmplVars['bx_if:show_buy'] = array(
            'condition' => $bSettings,
            'content' => array(
                'buy_url' => $aTmplVars['content_url'],
                'buy_title' => $sBuyTitle
            ) 
        );

        return $aTmplVars;
    }
}

/** @} */
