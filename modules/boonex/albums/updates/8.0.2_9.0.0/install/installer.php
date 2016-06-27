<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxAlbumsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install')
    		if(!$this->oDb->isFieldExists('bx_albums_albums', 'reports'))
        		$this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `reports` int(11) NOT NULL default '0' AFTER `comments`");

    	return parent::actionExecuteSql($sOperation);
    }
}
