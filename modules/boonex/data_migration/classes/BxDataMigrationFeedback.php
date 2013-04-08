<?php
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

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationFeedback extends BxDataMigrationData {

        function BxDataMigrationFeedback(&$oMigrationModule, &$rOldDb, $oDolModule = '') {
             parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        }

        function getMigration() {

            $this->setResultStatus('The transfer was started.');

               mysql_query('SET NAMES utf8', $this->rOldDb);

            $sSql = "SELECT
                    `ID` AS `id`,
                    `Sender` AS `author_id`,
                    `Header` AS `caption`,
                    `Text` AS `content`,
                    '' AS `uri`,
                    '' AS `tags`,
                    '3' AS `allow_comment_to`,
                    '3' AS `allow_vote_to`,
                    `Date` AS `date`,
                    `Active` AS `status`,
                    '0' AS `rate`,
                    '0' AS `rate_count`,
                    '0' AS `cmts_count`
                FROM `Stories`";
            $rResult = mysql_query($sSql, $this -> rOldDb);
            $iCount = mysql_num_rows($rResult);

            while($aFeedback = mysql_fetch_assoc($rResult)) {
                $aFeedback['uri'] = uriGenerate ($aFeedback['caption']
                    , $this->oDolModule->_oDb->getPrefix() . 'entries', 'uri');

                $aFeedback['status'] = $aFeedback['status'] == 'on' ? 0 : 1;

                if($this->exists($aFeedback['id'], $aFeedback['uri'])) {
                    $this->setResultStatus('Duplicate data. Feedback with similar info already exists.');
                    return MIGRATION_FAILED;
                }

                $sSql = "INSERT INTO `" . $this->oDolModule->_oDb->getPrefix() . "entries` SET";
                foreach($aFeedback as $sKey => $sValue)
                    $sSql .= " `" . $sKey . "`='" . $this -> oMigrationModule -> _oDb  -> escape($sValue) . "',";

                $iResult = (int)$this->oDolModule->_oDb->query(substr($sSql, 0, -1));
                if($iResult <= 0) {
                    $this->setResultStatus('Database error. Cannot insert data in the database.');
                    return MIGRATION_FAILED;
                }
            }

            $this->setResultStatus('The transfer was successfully completed. (' .  $iCount . ' items)');
            return MIGRATION_SUCCESSFUL;
        }

        function exists($iId, $sUri) {
            $iId = (int) $iId;
            $sUri = $this -> oMigrationModule -> _oDb  -> escape($sUri);

            $sSql = "SELECT `id` FROM `" . $this->oDolModule->_oDb->getPrefix() . "entries` WHERE `id`='" . $iId . "' OR `uri`='" . $sUri . "' LIMIT 1";
            return (int)$this->oDolModule->_oDb->getOne($sSql) != 0;
        }
    }