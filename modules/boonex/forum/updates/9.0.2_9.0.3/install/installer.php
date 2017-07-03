<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'text_comments'))
        		$this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `text_comments` text NOT NULL AFTER `text`");

			if($this->oDb->isIndexExists('bx_forum_discussions', 'title_text'))
				$this->oDb->query("ALTER TABLE `bx_forum_discussions` DROP INDEX `title_text`");

			$this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD FULLTEXT KEY `title_text` (`title`,`text`,`text_comments`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
