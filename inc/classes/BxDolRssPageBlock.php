<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolRssPageBlock extends BxTemplRss
{
    function __construct($aObject)
    {
        parent::__construct($aObject);
    }

    public function getUrl($mixedId) {
	    $oPageQuery = new BxDolPageQuery(array());

	    $sContent = $oPageQuery->getPageBlockContent($mixedId);
	    if(!$sContent)
			return false;

		list($sUrl) = explode('#', $sContent);
		if(!$sUrl)
			return false;

		return $sUrl;
    }
}

/** @} */
