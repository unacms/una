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

    bx_import('BxDolCategories');
    require_once 'BxDataMigrationData.php';

    class BxDataMigrationArticles extends BxDataMigrationData {
        var $_sType;

        function BxDataMigrationArticles(&$oMigrationModule, &$rOldDb, $oDolModule = '') {
             parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);

             $this->_sType = 'bx_' . $this->oDolModule->_oConfig->getUri();
        }

        function getMigration() {
            $this->setResultStatus('The transfer was started.');

                mysql_query('SET NAMES utf8', $this->rOldDb);

            //--- Transfer Categories ---//
            if($this->transferCategories() == MIGRATION_FAILED)
                return MIGRATION_FAILED;

            //--- Transfer Main Content ---//
            return $this->transferContent();
        }

        function transferCategories() {

            $sSql = "SELECT
                    `CategoryID` AS `id`,
                    `CategoryName` AS `name`
                FROM `ArticlesCategory`";
            $rResult = mysql_query($sSql, $this -> rOldDb);
            $iCount = mysql_num_rows($rResult);

            while($aCategory = mysql_fetch_assoc($rResult)) {

                if (get_mb_len($aCategory['name']) > 32)
                    $aCategory['name'] = get_mb_substr ($aCategory['name'], 0, 32);

                if($this->existsCategory($aCategory['name'], 0, $this->_sType))
                    continue;

                //escape all values
                $aCategory['name']=    $this -> oMigrationModule -> _oDb  -> escape($aCategory['name']);

                $sSql = "INSERT INTO `sys_categories`(`Category`, `ID`, `Type`, `Owner`, `Status`, `Date`)
                     VALUES('" . $aCategory['name'] . "', '0', '" . $this->_sType . "', '0', 'active', NOW())";

                $iResult = (int)$this->oDolModule->_oDb->query($sSql);
                if($iResult <= 0) {
                    $this->setResultStatus('Database error. Cannot insert category in the database.');
                    return MIGRATION_FAILED;
                }
            }

            return MIGRATION_SUCCESSFUL;
        }

        function transferContent() {
        $sSql = "SELECT
                    `ta`.`ArticlesID` AS `id`,
                    `ta`.`ownerID` AS `author_id`,
                    `ta`.`Title` AS `caption`,
                    `ta`.`Text` AS `content`,
                    `ta`.`ArticleUri` AS `uri`,
                    '' AS `tags`,
                    `tac`.`CategoryName` AS `categories`,
                    '0' AS `comment`,
                    '0' AS `vote`,
                    UNIX_TIMESTAMP(`Date`) AS `date`,
                    '0' AS `status`,
                    '0' AS `featured`,
                    '0' AS `rate`,
                    '0' AS `rate_count`,
                    '0' AS `view_count`,
                    '0' AS `cmts_count`
                FROM `Articles` AS `ta`
                LEFT JOIN `ArticlesCategory` AS `tac` ON `ta`.`CategoryID`=`tac`.`CategoryID`
                ORDER BY `Date` ASC";
            $rResult = mysql_query($sSql, $this -> rOldDb);
            $iCount = mysql_num_rows($rResult);

            $oCategories = new BxDolCategories();
            while($aItem = mysql_fetch_assoc($rResult)) {
                $aItem['uri'] = uriGenerate ($aItem['caption']
                    , $this->oDolModule->_oDb->getPrefix() . 'entries', 'uri');

                if($this->existsItem($aItem['id'], $aItem['uri'])) {
                    $this->setResultStatus('Duplicate data. Article with similar info already exists.');
                    return MIGRATION_FAILED;
                }

                $sSql = "INSERT INTO `" . $this->oDolModule->_oDb->getPrefix() . "entries` SET";
                foreach($aItem as $sKey => $sValue)
                    $sSql .= " `" . $sKey . "`='" . $this -> oMigrationModule -> _oDb  -> escape($sValue) . "',";

                $iResult = (int)$this->oDolModule->_oDb->query(substr($sSql, 0, -1));
                if($iResult <= 0) {
                    $this->setResultStatus('Database error. Cannot insert item in the database.');
                    return MIGRATION_FAILED;
                }

                $oCategories->reparseObjTags($this->_sType, $this->oDolModule->_oDb->lastId());
            }

            $this->setResultStatus('The transfer was successfully completed. (' .  $iCount . ' items)');
            return MIGRATION_SUCCESSFUL;
        }

        function existsCategory($sCategoryName, $iItemId, $sItemType) {
            $iItemId = (int) $iItemId;

            //-- escape all values --//
            $sCategoryName = $this -> oMigrationModule -> _oDb  -> escape($sCategoryName);
            $sItemType = $this -> oMigrationModule -> _oDb  -> escape($sItemType);
            //--

            $sSql = "SELECT `Category` FROM `sys_categories` WHERE `Category`='" . $sCategoryName . "' AND `ID`='" . $iItemId . "' AND `Type`='" . $sItemType . "' LIMIT 1";
            return (string)$this->oDolModule->_oDb->getOne($sSql) != '';
        }

        function existsItem($iId, $sUri) {
            $iId = (int) $iId;
            $sUri = $this -> oMigrationModule -> _oDb  -> escape($sUri);

            $sSql = "SELECT `id` FROM `" . $this->oDolModule->_oDb->getPrefix() . "entries` WHERE `id`='" . $iId . "' OR `uri`='" . $sUri . "' LIMIT 1";
            return (int)$this->oDolModule->_oDb->getOne($sSql) != 0;
        }
    }
