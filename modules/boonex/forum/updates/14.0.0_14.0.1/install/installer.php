<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }
    
    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_forum_covers', 'dimensions'))
                $this->oDb->query("ALTER TABLE `bx_forum_covers` ADD `dimensions` varchar(24) NOT NULL AFTER `size`");
            if(!$this->oDb->isFieldExists('bx_forum_files', 'dimensions'))
                $this->oDb->query("ALTER TABLE `bx_forum_files` ADD `dimensions` varchar(24) NOT NULL AFTER `size`");
            if(!$this->oDb->isFieldExists('bx_forum_photos', 'dimensions'))
                $this->oDb->query("ALTER TABLE `bx_forum_photos` ADD `dimensions` varchar(24) NOT NULL AFTER `size`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
