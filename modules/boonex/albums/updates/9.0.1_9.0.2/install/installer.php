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
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
