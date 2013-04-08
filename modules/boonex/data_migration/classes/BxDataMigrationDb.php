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

    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolModuleDb.php' );

    class BxDataMigrationDb extends BxDolModuleDb
    {
        var $_oConfig;
        var $sTablePrefix;

        /**
         * Constructor.
         */
        function BxDataMigrationDb(&$oConfig)
        {
            parent::BxDolModuleDb();

            $this -> _oConfig = $oConfig;
            $this -> sTablePrefix = $oConfig -> getDbPrefix();
        }

        /**
         * Function will create some config;
         *
         * @param  : $aConfig (array) - array with some of settings;
         * @return : void;
         */
        function createConfig($aConfig)
        {
            if( is_array($aConfig) ) {
                foreach($aConfig as $sName => $mValue)
                {
                    $mValue = $this -> escape($mValue);
                    $sName  = $this -> escape($sName);

                    // create new;
                    if( !$this -> getExtraParam($sName) ) {
                        $sQuery = "INSERT INTO `{$this -> sTablePrefix}config` SET `name` = '{$sName}', `value` = '{$mValue}'";
                        $this -> query($sQuery);
                    }
                    else {
                        // update exsisting;
                        $sQuery = "UPDATE `{$this -> sTablePrefix}config` SET  `value` = '{$mValue}' WHERE `name` = '{$sName}'";
                        $this -> query($sQuery);
                    }
                }
            }
        }

        /**
         * Function get transfer's status text;
         *
         * @param  : $sModule (string) - module's name;
         * @param  : $sStatus (string) - transfer's status;
         * @return : void;
         */
        function getTransferStatusText($sModule)
        {
            $sModule = $this -> escape($sModule);
            $sQuery = "SELECT `status_text` FROM `{$this->sTablePrefix}transfers` WHERE `module` = '{$sModule}' LIMIT 1";
            return $this -> getOne($sQuery);
        }

        /**
         * Function get transfer's status;
         *
         * @param  : $sModule (string) - module's name;
         * @param  : $sStatus (string) - transfer's status;
         * @return : void;
         */
        function getTransferStatus($sModule)
        {
            $sModule = $this -> escape($sModule);
            $sQuery = "SELECT `status` FROM `{$this->sTablePrefix}transfers` WHERE `module` = '{$sModule}' LIMIT 1";
            return $this -> getOne($sQuery);
        }

        /**
         * Function update transfer's status;
         *
         * @param  : $sModule (string) - module's name;
         * @param  : $sStatus (string) - transfer's status;
         * @return : void;
         */
        function updateTransferStatus($sModule, $sStatus)
        {
            $sModule = $this -> escape($sModule);
            $sStatus = $this -> escape($sStatus);
            $sQuery = "UPDATE `{$this->sTablePrefix}transfers` SET `status` = '{$sStatus}' WHERE `module` = '{$sModule}'";
            $this -> query($sQuery);
        }

        /**
         * Function will crete new transfer;
         *
         * @param  : $sModule (string) - module's name;
         * @return : void;
         */
        function createTransfer($sModule)
        {
            $sModule = $this -> escape($sModule);
            $sQuery = "INSERT INTO `{$this->sTablePrefix}transfers` SET `module` = '{$sModule}', `status` = 'not_started'";
            $this -> query($sQuery);
        }

        /**
         * Function will get the first transfer;
         *
         * @return : (string) - module's name;
         */
        function getFirstTransfer()
        {
            $sQuery = "SELECT `module` FROM `{$this->sTablePrefix}transfers` WHERE `status` = 'not_started' ORDER BY `id` LIMIT 1";
            return $this -> getOne($sQuery);
        }

        /**
         * Function will delete transfer from queue;
         *
         * @param  : $sModule (string) - module name;
         * @return : void;
         */
        function deleteTransfer($sModule)
        {
            $sModule = $this -> escape($sModule);
            $sQuery  = "DELETE FROM `{$this->sTablePrefix}transfers` WHERE `module` = '{$sModule}'";
            $this -> query($sQuery);
        }

        /**
         * Function will check transfer exsiting;
         *
         * @param  : $sModule (string) - module's name;
         * @return : (boolean) true if isset;
         */
        function checkTransfer($sModule)
        {
            $sModule = $this -> escape($sModule);
            $sQuery = "SELECT COUNT(*) FROM `{$this->sTablePrefix}transfers` WHERE `module` = '{$sModule}'";
            return $this -> getOne($sQuery) ? true : false;
        }

        /**
         * Function will get some config value;
         *
         * @param  : $sConfigName (sting) - config name;
         * @return : (mixed) - config value;
         */
        function getExtraParam($sConfigName)
        {
            $sConfigName = $this -> escape($sConfigName);
            return $this -> getOne("SELECT `value` FROM `{$this->sTablePrefix}config` WHERE `name` = '{$sConfigName}'");
        }

         /**
         * Function will set some config value;
         *
         * @param  : $sConfigName  (sting) - config name;
         * @param  : $mConfigValue (mixed) - config value;
         * @return : void;
         */
        function setExtraParam($sConfigName, $sConfigValue)
        {
            $sConfigName  = $this -> escape($sConfigName);
            $sConfigValue = $this -> escape($sConfigValue);

            if( !$this -> getOne("SELECT `value` FROM `{$this->sTablePrefix}config` WHERE `name` = '{$sConfigName}'") ) {
                // create new;
                $sQuery = "INSERT INTO `{$this->sTablePrefix}config` SET `name` = '{$sConfigName}', `value` = '{$sConfigValue}'";
            }
            else {
                 // update;
                $sQuery = "UPDATE `{$this->sTablePrefix}config` SET `value` = '{$sConfigValue}' WHERE `name` = '{$sConfigName}'";
            }

            $this -> query($sQuery);
        }

        /**
         * Function will connect to old dolphin Db base;
         *
         * @return : void;
         */
        function oldDbConnect()
        {
           $aConfig = array(
                'host'    => $this -> getExtraParam('config_host'),
                'user'      => $this -> getExtraParam('config_user'),
                'pass'    => $this -> getExtraParam('config_passwd'),
                'db_name' => $this -> getExtraParam('config_db'),
            );

            $rLink = mysql_connect($aConfig['host'], $aConfig['user'], $aConfig['pass'], true);
            mysql_select_db ($aConfig['db_name'], $rLink);

            return $rLink;
        }

        /**
         * Function will get nickname from old Db;
         *
         * @param  : $iProfileid (integer);
         * @return : (strng) - profile's nickname;
         */
        function getOldNickName($iProfileId)
        {
            $sQuery = "SELECT `NickName` FROM `Profiles` WHERE `ID` = $iProfileId";
            $rResult = mysql_query( $sQuery, $this -> oldDbConnect() );

             while( $aRow = mysql_fetch_assoc($rResult) )
             {
                 return $aRow['NickName'];
             }
        }
    }
