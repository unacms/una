<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_METATAGS_KEYWORDS_IN_CLOUD', 32); ///< default number of tags in tags cloud

/**
 * System services for metatags functionality.
 */
class BxBaseServiceMetatags extends BxDol
{
    protected $_iKeywordsCloudFontSizeMin = 14;
    protected $_iKeywordsCloudFontSizeMax = 32;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get keywords cloud.
     * @param $sObject metatgs object to get keywords cloud for
     * @param $mixedSection search section to refer when keyword is clicked, set the same as $sObject to show content withing the module only, it can be one value or array of values, leave empty to show all possible content upon keyword click
     * @param $iMaxCount number of tags in keywords cloud, by default @see BX_METATAGS_KEYWORDS_IN_CLOUD
     * @return tags cloud HTML string
     */
    public function serviceKeywordsCloud ($sObject, $mixedSection, $iMaxCount = BX_METATAGS_KEYWORDS_IN_CLOUD)
    {
        $o = BxDolMetatags::getObjectInstance($sObject);
        $aKeywords = $o->keywordsPopularList($iMaxCount);
        if (!$aKeywords)
            return '';

        ksort($aKeywords, SORT_LOCALE_STRING);

        $iFontDiff = floor($this->_iKeywordsCloudFontSizeMax - $this->_iKeywordsCloudFontSizeMin);
        $iMinRating = min($aKeywords);
        $iMaxRating = max($aKeywords);

        $iRatingDiff = $iMaxRating - $iMinRating;
        $iRatingDiff = $iRatingDiff == 0 ? 1 : $iRatingDiff;

        $sSectionPart = '';
        if (is_array($mixedSection))
            $sSectionPart = '&section[]=' . implode('&section[]=', $mixedSection);
        elseif (is_string($mixedSection))
            $sSectionPart = '&section[]=' . $mixedSection;

        $aUnits = array();
        foreach($aKeywords as $sKeyword => $iCount) {
            $aUnits[] = array(
                'size' => $this->_iKeywordsCloudFontSizeMin + floor($iFontDiff * (($iCount - $iMinRating) / $iRatingDiff)),
                'href' => BX_DOL_URL_ROOT . 'searchKeyword.php?type=keyword&keyword=' . rawurlencode($sKeyword) . $sSectionPart,
                'count' => $iCount,
                'keyword' => htmlspecialchars_adv($sKeyword),
            );
        }

        $aVars = array (
            'bx_repeat:units' => $aUnits,
        );

        $this->addCssJs();
        return BxDolTemplate::getInstance()->parseHtmlByName('metatags_keywords_cloud.html', $aVars);
    }

    /**
     * Get location map.
     * @param $sObject metatgs object to get keywords cloud for
     * @param $iId content id
     * @return map HTML string
     */
    public function serviceLocationsMap ($sObject, $iId, $sMapSize = '1000x144', $sMapKey = '')
    {
        $o = BxDolMetatags::getObjectInstance($sObject);
        $sLocationHtml = $o->locationsString($iId);
        if (!$sLocationHtml)
            return '';

        $sLocationEncoded = rawurlencode(strip_tags($sLocationHtml));
        $sProto = (0 == strncmp('https', BX_DOL_URL_ROOT, 5)) ? 'https' : 'http';
        $iScale = isset($_COOKIE['devicePixelRatio']) && (int)$_COOKIE['devicePixelRatio'] >= 2 ? 2 : 1;
        $sLang = BxDolLanguages::getInstance()->getCurrentLanguage();
        $aVars = array (
            'map_img' => $sProto . '://maps.googleapis.com/maps/api/staticmap?center=' . $sLocationEncoded . '&zoom=7&size=' . $sMapSize . '&maptype=roadmap&markers=size:small%7C' . $sLocationEncoded . '&scale=' . $iScale . '&language=' . $sLang  . ($sMapKey ? '&key=' . $sMapKey : ''),
            'location_string' => $sLocationHtml,
        );

        $this->addCssJs();
        return BxDolTemplate::getInstance()->parseHtmlByName('metatags_locations_map.html', $aVars);
    }

    public function addCssJs()
    {
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCss('metatags.css');
    }
}

/** @} */
