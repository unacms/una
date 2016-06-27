<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxPersonsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install')
    		if(!$this->oDb->isFieldExists('bx_persons_data', 'allow_view_to'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `allow_view_to` int(11) NOT NULL DEFAULT '3' AFTER `views`");

    	return parent::actionExecuteSql($sOperation);
    }
}
