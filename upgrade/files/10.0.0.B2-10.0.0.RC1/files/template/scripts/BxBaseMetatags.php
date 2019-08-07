<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Metatags objects representation.
 * 
 * @see BxDolMetatags
 */
class BxBaseMetatags extends BxDolMetatags
{
    protected $_oTemplate;

    protected $_iKeywordsCloudFontSizeMin = 14;
    protected $_iKeywordsCloudFontSizeMax = 32;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Get list of keywords associated with the content
     * @param $iId content id
     * @return string with content related keywords
     */
    public function getKeywordsList($iId, $iMaxCount = 0, $bAsArray = false)
    {
        $aKeywords = $this->keywordsGet($iId);
        if(!$aKeywords)
            return $bAsArray ? array() : '';

        sort($aKeywords, SORT_LOCALE_STRING);
        if($iMaxCount > 0 && count($aKeywords) > $iMaxCount)
            $aKeywords = array_slice($aKeywords, 0, $iMaxCount);

        $aUnits = array();
        foreach($aKeywords as $sKeyword) {
            $aUnits[] = array(
                'href' => $this->keywordsGetHashTagUrl($sKeyword, $iId),
                'keyword' => htmlspecialchars_adv($sKeyword),
            );
        }

        $aVars = array (
            'bx_repeat:units' => $aUnits,
        );

        if($bAsArray)
            return $aVars;

        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('metatags_keywords_list.html', $aVars);
    }

    public function getKeywordsCloud($mixedSection, $iMaxCount, $bAsArray = false)
    {
        $aKeywords = $this->keywordsPopularList($iMaxCount);
        if(!$aKeywords)
            return $bAsArray ? array() : '';

        ksort($aKeywords, SORT_LOCALE_STRING);

        $iFontDiff = floor($this->_iKeywordsCloudFontSizeMax - $this->_iKeywordsCloudFontSizeMin);
        $iMinRating = min($aKeywords);
        $iMaxRating = max($aKeywords);

        $iRatingDiff = $iMaxRating - $iMinRating;
        $iRatingDiff = $iRatingDiff == 0 ? 1 : $iRatingDiff;

        $aUnits = array();
        foreach($aKeywords as $sKeyword => $iCount) {
            $aUnits[] = array(
                'size' => $this->_iKeywordsCloudFontSizeMin + floor($iFontDiff * (($iCount - $iMinRating) / $iRatingDiff)),
                'href' => $this->keywordsGetHashTagUrl($sKeyword, 0, $mixedSection),
                'count' => $iCount,
                'keyword' => htmlspecialchars_adv($sKeyword),
            );
        }

        $aVars = array (
            'bx_repeat:units' => $aUnits,
        );

        if($bAsArray)
            return $aVars;

        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('metatags_keywords_cloud.html', $aVars);
    }

	public function getLocationsMap($iId, $sMapSize = '1000x144')
    {
        $sLocationHtml = $this->locationsString($iId);
        if(!$sLocationHtml)
			return '';

		$sMapKey = trim(getParam('sys_maps_api_key'));
        $sLocationEncoded = rawurlencode(strip_tags($sLocationHtml));
        $sProto = (0 === strncmp('https', BX_DOL_URL_ROOT, 5)) ? 'https' : 'http';
        $iScale = isset($_COOKIE['devicePixelRatio']) && (int)$_COOKIE['devicePixelRatio'] >= 2 ? 2 : 1;
        $sLang = bx_lang_name();

        $this->addCssJs();
        return $this->_oTemplate->parseHtmlByName('metatags_locations_map.html', array (
            'map_img' => $sProto . '://maps.googleapis.com/maps/api/staticmap?center=' . $sLocationEncoded . '&zoom=7&size=' . $sMapSize . '&maptype=roadmap&markers=size:small%7C' . $sLocationEncoded . '&scale=' . $iScale . '&language=' . $sLang  . ($sMapKey ? '&key=' . $sMapKey : ''),
            'location_string' => $sLocationHtml,
        ));
    }

    public function addCssJs()
    {
        $this->_oTemplate->addCss('metatags.css');
    }
}

/** @} */
