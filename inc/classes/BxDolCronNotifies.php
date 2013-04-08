<?php
// TODO: move massmaler to separate module

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( BX_DIRECTORY_PATH_INC . 'db.inc.php' );
require_once('BxDolCron.php');

class BxDolCronNotifies extends BxDolCron {
    function processing() {
        set_time_limit( 36000 );
        ignore_user_abort();

        $sResult = "";
        $iPerStart = (int)trim(getParam('msgs_per_start'));

        $iFullCount = (int)$GLOBALS['MySQL']->getOne('SELECT COUNT(*) FROM `sys_sbs_queue`');
        if($iFullCount) {
            $iProcess = $iFullCount < $iPerStart ? $iFullCount : $iPerStart;

            $sResult .= "\n- Start email send -\n";
            $sResult .= "Total queued emails: " . $iFullCount . "\n";
            $sResult .= "Ready for send: " . $iProcess . "\n";

            $aMails = $GLOBALS['MySQL']->getAll("SELECT `id`, `email`, `subject`, `body` FROM `sys_sbs_queue` ORDER BY `id` LIMIT 0, " . $iProcess);

            $iSent = 0;
            $aIds = array();
            foreach($aMails as $aMail) {
                $aIds[] = $aMail['id'];
                if(sendMail($aMail['email'], $aMail['subject'], $aMail['body'], 0, array(), BX_EMAIL_MASS))
                    $iSent++;
                else
                    $sResult .= "Cannot send message to " . $aMail['email'] . "\n";
            }
            $GLOBALS['MySQL']->query("DELETE FROM `sys_sbs_queue` WHERE `id` IN ('" . implode("','", $aIds) . "')");

            $sResult .= "Processed emails: " . $iSent . "\n";
            sendMail(getParam('site_email'), getParam('site_title') . ": Periodic Report", $sResult, 0, array(), BX_EMAIL_NOTIFY, 'text'); // TODO: email template
        }
    }
}
