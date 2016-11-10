<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

define('BX_METATAGS_KEYWORDS_IN_CLOUD', 32); ///< default number of tags in tags cloud

/**
 * System services for metatags functionality.
 */
class BxBaseServiceMetatags extends BxDol
{
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
    public function serviceKeywordsCloud($sObject, $mixedSection, $aParams = array())
    {
    	$iMaxCount = isset($aParams['max_count']) ? (int)$aParams['max_count'] : BX_METATAGS_KEYWORDS_IN_CLOUD;
    	$bShowEmpty = isset($aParams['show_empty']) ? (bool)$aParams['show_empty'] : false;

        $sResult = BxDolMetatags::getObjectInstance($sObject)->getKeywordsCloud($mixedSection, $iMaxCount);
        if(empty($sResult))
			return $bShowEmpty ? MsgBox(_t('_Empty')) : '';

		return $sResult;
    }

    /**
     * Get location map.
     * @param $sObject metatgs object to get keywords cloud for
     * @param $iId content id
     * @return map HTML string
     */
    public function serviceLocationsMap($sObject, $iId, $sMapSize = '1000x144')
    {
        return BxDolMetatags::getObjectInstance($sObject)->getLocationsMap($iId, $sMapSize);
    }
}

/** @} */
