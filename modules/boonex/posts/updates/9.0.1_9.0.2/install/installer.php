<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPostsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_posts_posts', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->isIndexExists('bx_posts_cmts', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_posts_cmts` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_posts_cmts` ADD FULLTEXT KEY `search_fields` (`cmt_text`)");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
