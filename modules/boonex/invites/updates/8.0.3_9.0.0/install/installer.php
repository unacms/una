<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxInvUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install')
    		if(!$this->oDb->isFieldExists('bx_inv_invites', 'key'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `key` varchar(128) collate utf8_unicode_ci NOT NULL AFTER `profile_id`");

    	return parent::actionExecuteSql($sOperation);
    }
}
