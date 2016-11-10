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

    protected $_sBrowseUrl;
    protected $_iKeywordsCloudFontSizeMin = 14;
    protected $_iKeywordsCloudFontSizeMax = 32;

    public function __construct ($aObject, $oTemplate = null)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

		$this->_sBrowseUrl = bx_append_url_params('searchKeyword.php', array(
    		'type' => 'keyword', 
    		'keyword' => '{keyword}'
    	)) . '{sections}';
    }

    public function getKeywordsCloud($mixedSection, $iMaxCount)
    {
        $aKeywords = $this->keywordsPopularList($iMaxCount);
        if(!$aKeywords)
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
                'href' => BX_DOL_URL_ROOT . bx_replace_markers($this->_sBrowseUrl, array(
            		'keyword' => rawurlencode($sKeyword),
            		'sections' => $sSectionPart
            	)),
                'count' => $iCount,
                'keyword' => htmlspecialchars_adv($sKeyword),
            );
        }

        $aVars = array (
            'bx_repeat:units' => $aUnits,
        );

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
