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

    public function getKeywordsCloud($mixedSection, $iMaxCount, $bAsArray = false, $aParams = [])
    {
        $aKeywords = $this->keywordsPopularList($iMaxCount, isset($aParams['context_id']) ? $aParams['context_id'] : 0);
        if(!$aKeywords)
            return $bAsArray ? array() : '';
        
        if (isset($aParams['menu_view']) && $aParams['menu_view'] == true){
            $oMenu = BxDolMenu::getObjectInstance('sys_tags_cloud');
            arsort($aKeywords);
            $oMenu->setKeywords($aKeywords, $this, $mixedSection);
            return $oMenu->getCode();
        }

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

	public function getLocationsMap($iId, $aParams = array())
    {
        $aLocation = $this->locationGet($iId);
        if(!$aLocation)
            return '';

        $sLocationHtml = $this->locationsString($iId);
        if(!$sLocationHtml)
			return '';

        $o = BxDolLocationMap::getObjectInstance(getParam('sys_location_map_default'),  $this->_oTemplate);
        if(!$o)
			return '';

        return $o->getMapSingle($aLocation, $sLocationHtml, $aParams);
    }

    public function addCssJs()
    {
        $this->_oTemplate->addCss('metatags.css');
    }
}

/** @} */
