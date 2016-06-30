<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxPostsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install')
    		if(!$this->oDb->isFieldExists('bx_posts_posts', 'reports'))
        		$this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `reports` int(11) NOT NULL default '0' AFTER `comments`");

    	return parent::actionExecuteSql($sOperation);
    }
}
