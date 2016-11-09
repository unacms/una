<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRssBoonEx extends BxTemplRss
{
	protected $aFeeds;

    public function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->aFeeds = array (
		    'boonex_news' => 'http://www.boonex.com/unity/blog/featured_posts/?rss=1'
		);
    }

    public function getUrl($mixedId) {
		if(!isset($this->aFeeds[$mixedId]))
			return false;

		return $this->aFeeds[$mixedId];
    }
}

/** @} */
