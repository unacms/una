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
     * @param $iMaxCount number of tags in keywords cloud, by default @see BX_METATAGS_KEYWORDS_IN_CLOUD
     * @return tags cloud HTML string
     */
    public function serviceKeywordsCloud ($sObject, $sSection, $iMaxCount = BX_METATAGS_KEYWORDS_IN_CLOUD)
    {
        bx_import('BxDolMetatags');
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

        $aUnits = array();
        foreach($aKeywords as $sKeyword => $iCount) {
            $aUnits[] = array(
                'size' => $this->_iKeywordsCloudFontSizeMin + floor($iFontDiff * (($iCount - $iMinRating) / $iRatingDiff)),
                'href' => BX_DOL_URL_ROOT . 'searchKeyword.php?type=keyword&keyword=' . rawurlencode($sKeyword) . '&section[]=' . $sSection,
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

    public function addCssJs()
    {
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addCss('metatags.css');
    }
}

/** @} */
