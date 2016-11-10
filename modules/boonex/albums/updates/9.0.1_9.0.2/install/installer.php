<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAlbumsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_albums_albums', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'favorites'))
	        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->isIndexExists('bx_albums_files2albums', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_albums_files2albums` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD FULLTEXT `search_fields` (`title`)");

			if ($this->isIndexExists('bx_albums_cmts', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_albums_cmts` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_albums_cmts` ADD FULLTEXT `search_fields` (`cmt_text`)");

			if ($this->isIndexExists('bx_albums_cmts_media', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_albums_cmts_media` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_albums_cmts_media` ADD FULLTEXT `search_fields` (`cmt_text`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
