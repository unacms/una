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

    class BxDataMigrationData
    {
        var $oMigrationModule;
        var $rOldDb;
        var $oDolModule;

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationData(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
             $this -> oMigrationModule = $oMigrationModule;
             $this -> rOldDb  = $rOldDb;
             $this -> oDolModule = $oDolModule ? $oDolModule : '';
        }

        /**
         * Function will migrate data;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            $this -> setResultStatus('System error: method getMigration is not redefined');
            return MIGRATION_FAILED;
        }

        /**
         * Function set operation status;
         *
         * @return : void;
         */
        function setResultStatus($sStatus)
        {
            $sStatus = $this -> oMigrationModule -> _oDb -> escape($sStatus);
            $sQuery =
            "
                UPDATE
                    `{$this -> oMigrationModule -> _oDb -> sTablePrefix}transfers`
                SET
                    `status_text` = '{$sStatus}'
                WHERE
                    `module` = '{$this -> oMigrationModule -> sProcessedModule}'
             ";

            $this -> oMigrationModule -> _oDb -> query($sQuery);
        }

        // shortcut to escape
        function escape($sText) {
            return $this->oMigrationModule->_oDb->escape($sText);
        }

        /**
         * Escape all strings values;
         *
         * @param $aRow array;
         * @return array - escaped data;
         */
        function escapeData($aRow)
        {
            foreach($aRow as $sKey => $mValue)
            {
                $aRow[$sKey] = !is_numeric($mValue)
                    ? $this->escape($mValue)
                    : $mValue;
            }

            return $aRow;
        }

        // shortcut to query
        function query($sQuery) {
            //echoDbg($sQuery); return;
            return $this->oMigrationModule->_oDb->query($sQuery);
        }

        // shortcut to getOne
        function getOne($sQuery) {
            return $this->oMigrationModule->_oDb->getOne($sQuery);
        }

        // shortcut to lastId
        function lastId() {
            return $this->oMigrationModule->_oDb->lastId();
        }
    }