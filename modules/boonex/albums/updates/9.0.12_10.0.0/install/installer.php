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
            if(!$this->oDb->isFieldExists('bx_albums_albums', 'rrate'))
        	        $this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `rrate` float NOT NULL default '0' AFTER `votes`");
            if(!$this->oDb->isFieldExists('bx_albums_albums', 'rvotes'))
        	        $this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");

            if(!$this->oDb->isFieldExists('bx_albums_files', 'duration'))
        	        $this->oDb->query("ALTER TABLE `bx_albums_files` ADD `duration` int(11) NOT NULL AFTER `size`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
