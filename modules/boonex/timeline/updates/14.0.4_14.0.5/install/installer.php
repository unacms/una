<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxTimelineUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }
    
    public function actionExecuteSql($sOperation)
    {
        $oDb = BxDolDb::getInstance();
        $aEvents = $oDb->getAll("SELECT * FROM `bx_timeline_events` WHERE `source`='' AND `content` LIKE '%timeline_group%'");
        foreach($aEvents as $aEvent) {
            if(empty($aEvent['content']))
                continue;

            $aContent = unserialize($aEvent['content']);
            if(!empty($aContent['timeline_group']) && is_array($aContent['timeline_group']))
                $oDb->query("UPDATE `bx_timeline_events` SET `source`=:source WHERE `id`=:id", [
                    'id' => $aEvent['id'],
                    'source' => $aContent['timeline_group']['by']
                ]);
        }

        return parent::actionExecuteSql($sOperation);
    }
}
