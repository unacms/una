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
            if(BxDolModuleQuery::getInstance()->isModuleByName('bx_notifications'))
                $this->oDb->query("DELETE FROM `bx_notifications_settings` WHERE `title` IN ('_bx_albums_alert_action_media_added_follow_context', '_bx_albums_alert_action_media_added_follow_member')");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
