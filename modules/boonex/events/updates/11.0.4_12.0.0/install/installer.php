<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxEventsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_events_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_events_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_events_admins', 'role'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `role` int(10) unsigned NOT NULL default '0' AFTER `fan_id`");
            if(!$this->oDb->isFieldExists('bx_events_admins', 'added'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `added` int(11) unsigned NOT NULL default '0' AFTER `role`");

            if(!$this->oDb->isFieldExists('bx_events_favorites_track', 'added'))
                $this->oDb->query("ALTER TABLE `bx_events_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");

            $aEvents = $this->oDb->getAll('SELECT `id`, `date_start`, `date_end`, `timezone` FROM `bx_events_data` ORDER BY `id`');
            if ($aEvents) {
                foreach ($aEvents as $aEvent) {
                   if (!$aEvent['timezone'] || 'UTC' == $aEvent['timezone'])
                        continue;

                    $oDateStart = $aEvent['date_start'] ? date_create('@' . $aEvent['date_start']) : 0;
                    $oDateEnd = $aEvent['date_end'] ? date_create('@' . $aEvent['date_end']) : 0;
                    if (!$oDateStart && !$oDateEnd)
                        continue;

                    $oTz = timezone_open($aEvent['timezone']);
                    if (!$oTz)
                        continue;
                    $iOffset = timezone_offset_get($oTz, $oDateStart ? $oDateStart : $oDateEnd);

                    if ($aEvent['date_start'])
                        $this->oDb->query("UPDATE `bx_events_data` SET `date_start` = :date WHERE `id` = :id", array('date' =>  $aEvent['date_start'] - $iOffset, 'id' => $aEvent['id']));
                    if ($aEvent['date_end'])
                        $this->oDb->query("UPDATE `bx_events_data` SET `date_end` = :date WHERE `id` = :id", array('date' =>  $aEvent['date_end'] - $iOffset, 'id' => $aEvent['id']));
                }
            }
        }

        return parent::actionExecuteSql($sOperation);
    }
}
