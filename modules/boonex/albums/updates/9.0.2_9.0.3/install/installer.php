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
    		if(!$this->oDb->isFieldExists('bx_albums_albums', 'featured'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `featured` int(11) NOT NULL default '0' AFTER `reports`");

			if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'featured'))
	        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `featured` int(11) NOT NULL default '0' AFTER `comments`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
