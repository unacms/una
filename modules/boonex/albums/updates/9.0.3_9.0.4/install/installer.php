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
    		if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'author'))
        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `author` int(10) unsigned NOT NULL AFTER `file_id`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
