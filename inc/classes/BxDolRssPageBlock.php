<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRssPageBlock extends BxTemplRss
{
    public function __construct($aObject)
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
