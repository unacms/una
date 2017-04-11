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

    public function getSctInclude($aSettings)
    {
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

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

        return $this->parseHtmlByName('sct_button.html', array(
            'bx_repeat:items' => array(
                array('key' => 'id', 'value' => $aContentInfo[$CNF['FIELD_ID']]),
                array('key' => 'name', 'value' => bx_html_attribute($aContentInfo[$CNF['FIELD_TITLE']])),
                array('key' => 'price', 'value' => sprintf('%01.2f', $aContentInfo[$CNF['FIELD_PRICE']])),
                array('key' => 'weight', 'value' => $aContentInfo[$CNF['FIELD_WEIGHT']]),
                array('key' => 'url', 'value' => $sUrl),
                array('key' => 'description', 'value' => bx_html_attribute(strip_tags($aContentInfo[$CNF['FIELD_TEXT']]))),
            )
        ));
    }
}

/** @} */
