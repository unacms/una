<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplRss');

class BxDolRssBoonEx extends BxTemplRss
{
	protected $aFeeds;

    function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->aFeeds = array (
        	'boonex_version' => 'http://rss.boonex.com/',

		    'boonex_news' => 'http://www.boonex.com/unity/blog/featured_posts/?rss=1',
		    'boonex_unity_market' => 'http://www.boonex.com/unity/extensions/latest/?rss=1',
		    'boonex_unity_lang_files' => 'http://www.boonex.com/unity/extensions/tag/translations&rss=1',
		    'boonex_unity_market_templates' => 'http://www.boonex.com/unity/extensions/tag/templates&rss=1',
		    'boonex_unity_market_featured' => 'http://www.boonex.com/unity/extensions/featured_posts?rss=1',
		);
    }

    public function getUrl($mixedId) {
		if(!isset($this->aFeeds[$mixedId]))
			return false;

		return $this->aFeeds[$mixedId];
    }
}

/** @} */
