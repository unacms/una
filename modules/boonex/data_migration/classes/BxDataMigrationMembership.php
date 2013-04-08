<?php

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationMembership extends BxDataMigrationData
    {
        var $iTransffered = 0;

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationSounds(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        }

        /**
         * Function migrate all membership;
         *
         * @return : (string) - error message or empty;
         */
        function getMigration()
        {
            // set new status;
            $this -> setResultStatus('All membership levels transfer now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            $sError = $this -> _exportMemLevels();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            $sError = $this -> _exportPofileMemLevels();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('All membership levels were transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * export all MemLevels;
         *
         * @return : (string) - error message or empty;
         */
        function _exportMemLevels()
        {
            $sQuery = "SELECT * FROM `MemLevels`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( true == ($aRow = mysql_fetch_assoc($rResult)) )
            {
                if( !$this -> isLevelExisting($aRow['Name']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    $sQuery =
                    "
                        INSERT INTO
                            `sys_acl_levels`
                        SET
                            `Name`            = '{$aRow['Name']}',
                            `Active`       = '{$aRow['Active']}',
                            `Purchasable` = '{$aRow['Purchasable']}',
                            `Removable`   = '{$aRow['Removable']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        $this -> setResultStatus('Database error. Cannot insert new acl level in the database.');
                        return MIGRATION_FAILED;
                    }

                    $this -> iTransffered++;
                }
            }
        }

        /**
         * export all ProfileMemLevels;
         *
         * @return : (string) - error message or empty;
         */
        function _exportPofileMemLevels()
        {
           $sQuery = "SELECT * FROM `ProfileMemLevels`";
           $rResult = mysql_query($sQuery, $this -> rOldDb);

           while( true == ($aRow = mysql_fetch_assoc($rResult)) )
           {
               if( !$this -> isProfileLevelExisting($aRow['IDMember'], $aRow['IDLevel']) ) {
                    // escape all data;
                    $aRow     = $this -> escapeData($aRow);
                    $iLevelId = $this -> getNewMemLevelId($aRow['IDLevel']);

                    //-- define extra query --//

                    $sExtraSQL = '';
                    if($aRow['DateExpires']) {
                        $sExtraSQL .= " `DateExpires`   = '{$aRow['DateExpires']}',";
                    }

                    if($aRow['TransactionID']) {
                        $sExtraSQL .= " `TransactionID`   = '{$aRow['TransactionID']}',";
                    }
                    //--

                    $sQuery   =
                    "
                        INSERT INTO
                            `sys_acl_levels_members`
                        SET
                            `IDMember`      = '{$aRow['IDMember']}',
                            `IDLevel`         = '{$iLevelId}',
                            `DateStarts`    = '{$aRow['DateStarts']}',{$sExtraSQL}";

                   $iResult = (int) $this -> oMigrationModule -> _oDb -> query( trim($sQuery,','));
                   if($iResult <= 0) {
                        $this -> setResultStatus('Database error. Cannot insert new profile mem level in the database.');
                        return MIGRATION_FAILED;
                   }
               }
           }
        }

        /**
         * Define new level id;
         *
         * @return integer;
         */
        function getNewMemLevelId($iOldLevelId)
        {
            $iOldLevelId = (int) $iOldLevelId;

            $sQuery  = "SELECT `Name` FROM `MemLevels` WHERE `ID` = {$iOldLevelId}";
            $rResult = mysql_query($sQuery, $this -> rOldDb);
            $aRow    = mysql_fetch_assoc($rResult);
            $aRow    = $this -> escapeData($aRow);

            // get new level id;
            $sQuery = "SELECT `ID` FROM `sys_acl_levels` WHERE `Name` = '{$aRow['Name']}' LIMIT 1";
            return (int) $this -> oMigrationModule -> _oDb -> getOne($sQuery);
        }

       /**
        * Check existing membership level;
        *
        * @param $sLevelName string;
        * @return  true if exist;
        */
       function isLevelExisting($sLevelName)
       {
           $sLevelName = $this -> oMigrationModule -> _oDb  -> escape($sLevelName);
           $sQuery = "SELECT COUNT(*) FROM `sys_acl_levels` WHERE `Name` = '{$sLevelName}'";
           return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
       }

       /**
        * Check existing profile membership level;
        *
        * @param $iProfileId integer;
        * @param $iLevelId integer;
        * @return  true if exist;
        */
       function isProfileLevelExisting($iProfileId, $iLevelId)
       {
           $iProfileId = (int) $iProfileId;
           $iLevelId   = (int) $iLevelId;

           $sQuery = "SELECT COUNT(*) FROM `sys_acl_levels_members` WHERE `IDMember` = {$iProfileId} AND `IDLevel` = {$iLevelId}";
           return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
       }
    }