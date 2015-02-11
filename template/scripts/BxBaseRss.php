<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Privacy representation.
 * @see BxDolPrivacy
 */
class BxBaseRss extends BxDolRss
{
    protected $_oTemplate;

    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function getFeed($mixedId, $iUserId = 0)
    {
    	$sUrl = $this->getUrl($mixedId);

    	$aMarkers = array('SiteUrl' => BX_DOL_URL_ROOT);

    	if($iUserId) {
	        $oProfile = BxDolProfile::getInstance($iUserId);
	        if(!$oProfile)
	            $oProfile = BxDolProfileUndefined::getInstance();

	        $aMarkers['NickName'] =  $oProfile->getDisplayName();
		}

    	$sUrl = bx_replace_markers($sUrl, $aMarkers);

		header('Content-Type: text/xml; charset=utf-8');
		return bx_file_get_contents($sUrl . (defined('BX_PROFILER') && BX_PROFILER && 0 == strncmp(BX_DOL_URL_ROOT, $sUrl, strlen(BX_DOL_URL_ROOT)) ? '&bx_profiler_disable=1' : ''));
    }

	/**
     * Outputs holder html for dynamically loaded RSS.
     * It automatically adds necessary js, css files and make injection into HTML HEAD section.
     * @param $mixedRssId - system rss name, or current block id (if inserted into builder page)
     * @param $iRssNum - numbr of rss items to disolay
     * @param $iMemberId - optional member id
     */
    public function getHolder($mixedRssId, $iRssNum, $iMemberId = 0, $bInit = true)
    {
        if($bInit && !self::$bInitialized) {
			$this->_addJsCss();
            $this->_addInjection();

            self::$bInitialized = true;
        }

        return $this->_oTemplate->parseHtmlByName('rss_holder.html', array (
        	'rss_object' => $this->_sObject,
            'rss_id' => $mixedRssId,
            'rss_num' => $iRssNum,
            'member_id' => $iMemberId,
        ));
    }

    protected function _addJsCss()
    {
        $this->_oTemplate->addCss(array('rss.css'));
        $this->_oTemplate->addJs(array(
			'jquery.jfeed.pack.js',
            'jquery.dolRSSFeed.js',
		));
    }

    protected function _addInjection()
    {
    	$sContent = $this->_oTemplate->_wrapInTagJsCode('$(document).ready(function() {$("div.RSSAggrCont").dolRSSFeed();});');
    	$this->_oTemplate->addInjection ('injection_head', 'text', $sContent);
    }
}

/** @} */
