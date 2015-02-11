<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolRssBoonEx extends BxTemplRss
{
	protected $aFeeds;

    function __construct($aObject)
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
