<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolSocialSharingQuery');

/**
 * @page objects
 * @section social_sharing Social Sharing Buttons
 * @ref BxDolSocialSharing
 */

/**
 * Social sharing buttons for any content
 *
 * It displays sharing buttons from popular social networks, like facebook, twitter, gogole plus, etc.
 *
 *
 * @section example Example of usage:
 *
 * @code
 * bx_import('BxTemplSocialSharing');
 * echo BxTemplSocialSharing::getInstance()->getCode($iId, $sModuleName, $sUrl, $sTitle);
 * @endcode
 *
 *
 * @section alerts Alerts:
 *
 * Type/unit: system
 * Action: social_sharing_display
 * Options:
 *      buttons - reference to buttons array
 *      markers - reference to variables for replacement
 *      override_output - override output string
 *
 */
class BxDolSocialSharing extends BxDol implements iBxDolSingleton
{
    protected $_oQuery;
    protected $_aSocialButtons = array (); // active social buttons array

    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct();
        $this->_oQuery = new BxDolSocialSharingQuery();
        $this->_aSocialButtons = $this->_oQuery->getActiveButtons();
    }

    /**
     * Get object instance
     * @param $sObject object name
     * @return object instance
     */
    static public function getInstance()
    {
        if (isset($GLOBALS['bxDolClasses']['BxDolSocialSharing']))
            return $GLOBALS['bxDolClasses']['BxDolSocialSharing'];

        bx_import('BxTemplSocialSharing');
        $o = new BxTemplSocialSharing();

        return ($GLOBALS['bxDolClasses']['BxDolSocialSharing'] = $o);
    }

    public function getCode ($sContentId, $sModuleName, $sUrl, $sTitle, $aCustomVars = false)
    {
        // overrided in template class
    }

    /**
     * Replace provided markers in string.
     * @param $s - string to replace markers in
     * @param $a - markers array
     * @return string with replaces markers
     */
    protected function _replaceMarkers ($mixed, $aMarkers)
    {
        if (empty($mixed) || empty($aMarkers) || !is_array($aMarkers))
            return $mixed;
        return bx_replace_markers($mixed, $aMarkers);
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
