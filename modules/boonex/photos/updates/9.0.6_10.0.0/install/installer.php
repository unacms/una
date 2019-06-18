<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPhotosUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'rrate'))
        	        $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `rrate` float NOT NULL default '0' AFTER `svotes`");
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'rvotes'))
        	        $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
