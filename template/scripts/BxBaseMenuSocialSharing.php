<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu for content social sharing
 */
class BxBaseMenuSocialSharing extends BxTemplMenu
{
    protected $_aNeedEncode = array(
    	'url' => 'url_encoded',
    	'title' => 'title_encoded'
    );

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $sLang = BxDolLanguages::getInstance()->getCurrentLanguage();
        $this->addMarkers(array (
            'lang' => $sLang,
            'locale' => $this->_getLocaleFacebook($sLang),
        ));
    }

    public function addMarkers($a)
    {
        if(!empty($this->_aNeedEncode) && is_array($this->_aNeedEncode))
            foreach($this->_aNeedEncode as $sKey1 => $sKey2)
                if(key_exists($sKey1, $a))
                    $a[$sKey2] = rawurlencode($a[$sKey1]);

        return parent::addMarkers($a); 
    }

    public function getCode()
    {
        if (!isset($this->_aObject['menu_items']))
            $this->_aObject['menu_items'] = $this->getMenuItemsRaw();

        // alert
        $sOverrideOutput = null;
        $oAlert = new BxDolAlerts('system', 'social_sharing_display', '', '', array (
        	'markers' => &$this->_aMarkers,
            'buttons' => &$this->_aObject['menu_items'],
            'override_output' => &$sOverrideOutput,
        ));
        $oAlert->alert();

        // return custom code if there is one
        if ($sOverrideOutput)
            return $sOverrideOutput;

        //return empty string of there is no buttons
        if (empty($this->_aObject['menu_items']))
            return '';

        return parent::getCode();
    }
    
	/**
     * Get most facebook locale for provided language code.
     * @param $sLang lang code
     * @return locale string or empty string if no lacale is found
     */
    protected function _getLocaleFacebook ($sLang)
    {
        $aLocales = $this->_getLocalesFacebook();
        if (!$aLocales || !isset($aLocales[$sLang]))
            return '';
        return $aLocales[$sLang];
    }

    /**
     * Get facebook locales
     * @return locales array, lang is array key and locale is array value
     */
    protected function _getLocalesFacebook ()
    {
        $oCache = $this->_oQuery->getDbCacheObject();
        $sCacheKey = $this->_oQuery->genDbCacheKey('sys_social_sharing_locales_fb');
        $aData = $oCache->getData($sCacheKey);
        if (null === $aData) {
            $sXML = bx_file_get_contents ('http://www.facebook.com/translations/FacebookLocales.xml');
            if (!$sXML)
                return false;
            $xmlLocates = new SimpleXMLElement($sXML);
            $aData = array ();
            foreach ($xmlLocates->locale as $xmlLocale) {
                $sLocale = (string)($xmlLocale->codes->code->standard->representation);
                list ($sLang,) = explode('_', $sLocale);
                if (!isset($aData[$sLang]))
                    $aData[$sLang] = $sLocale;
            }
            $oCache->setData ($sCacheKey, $aData);
        }
        return $aData;
    }
}

/** @} */
