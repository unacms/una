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
    		if(!$this->oDb->isFieldExists('bx_albums_albums', 'score'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `score` int(11) NOT NULL default '0' AFTER `votes`");
			if(!$this->oDb->isFieldExists('bx_albums_albums', 'sc_up'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `sc_up` int(11) NOT NULL default '0' AFTER `score`");
			if(!$this->oDb->isFieldExists('bx_albums_albums', 'sc_down'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `sc_down` int(11) NOT NULL default '0' AFTER `sc_up`");

			if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'score'))
        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `score` int(11) NOT NULL default '0' AFTER `votes`");
			if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'sc_up'))
        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `sc_up` int(11) NOT NULL default '0' AFTER `score`");
			if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'sc_down'))
        		$this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `sc_down` int(11) NOT NULL default '0' AFTER `sc_up`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
