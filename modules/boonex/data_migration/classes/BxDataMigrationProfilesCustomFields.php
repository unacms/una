<?php

    require_once 'BxDataMigrationData.php';
    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolPFM.php' );

    require_once( BX_DIRECTORY_PATH_INC . 'prof.inc.php' );

    class BxDataMigrationProfilesCustomFields extends BxDataMigrationData
    {
        var $iTransffered = 0;

        var $aOldProfileTable = array();
        var $aNewProfileTable = array();

        var $aFiltered = array();

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationProfilesCustomFields(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
            $this -> aFiltered = array(
                'PrimPhoto', 'Picture',
            );
        }

        /**
         * Function migrate profiles custom fields;
         *
         * @return : (string) operation result;
         */
        function getMigration()
        {
            // set new status;
            $this -> setResultStatus('Profiles custom fields transfer now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            //-- get old table structure --//
            $sQuery = "DESCRIBE `Profiles`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);
            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                 $this -> aOldProfileTable[$aRow['Field']] = array (
                     'Type' => $aRow['Type'],
                     'Null' => $aRow['Null'],
                     'Key'  => $aRow['Key'],
                     'Default' => $aRow['Default'],
                     'Extra' => $aRow['Extra'],
                 );
            }
            //--

            //-- get new table structure --//
            $sQuery = "DESCRIBE `Profiles`";
            $aRow = $this -> oMigrationModule -> _oDb -> getAll($sQuery);
            foreach($aRow as $iKey => $aItems)
            {
                $this -> aNewProfileTable[$aItems['Field']] = array (
                     'Type' => $aItems['Type'],
                     'Null' => $aItems['Null'],
                     'Key'  => $aItems['Key'],
                     'Default' => $aItems['Default'],
                     'Extra' => $aItems['Extra'],
                 );
            }
           //--

           //-- compare the recived array --//
           foreach($this -> aOldProfileTable as $sKey => $aItems)
           {
                if(array_key_exists($sKey, $this -> aNewProfileTable) || in_array($sKey, $this -> aFiltered)) {
                   unset($this -> aOldProfileTable[$sKey]);
                }
           }
           //--

           //-- alter new table --//
           foreach($this -> aOldProfileTable as $sKey => $aItems)
           {
               // define dafault value;
               $sDefault = $aItems['Default']
                   ? "DEFAULT '{$aItems['Default']}'"
                   : '';

               $sNull  = $aItems['Null'] == 'NO'
                      ? 'NOT NULL'
                       : '';

               $sQuery =
               "
                       ALTER TABLE `Profiles` ADD `{$sKey}` {$aItems['Type']} {$sNull} {$sDefault}
               ";

               // add new field
               $this -> oMigrationModule -> _oDb -> query($sQuery);

               // transfer  into sys_profile_fields
               $this -> _transferProfileFields($sKey);

               // transfer  into sys_pre_values
               $this -> _transferPreValues($sKey);

               $this -> iTransffered++;
           }
           //--

           //-- transfer all data --//
           $this -> _transformData();
           //--

           //-- Recompile all needed cache files --//

           bx_import('BxDolInstallerUtils');
           $oInstallerUtils = new BxDolInstallerUtils();
           $oInstallerUtils->updateProfileFieldsHtml();

           $oCacher = new BxDolPFMCacher();
           $oCacher -> createCache();

           $this -> compilePreValues();

           //--

           // set as finished;
           $this -> setResultStatus('All profiles custom fields were transferred (' .  $this -> iTransffered . ' items)');

           return MIGRATION_SUCCESSFUL;
        }

        /**
         * Transfer all linked values from prevalues
         *
         * @param $sFieldName strings
         * @return void
         */
        function _transferPreValues($sFieldName)
        {
            $sFieldName = $this -> escape($sFieldName);

            $sQuery = "SELECT * FROM `Prevalues` WHERE `Key` = '{$sFieldName}'";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
               // escape all data;
               $aRow = $this -> escapeData($aRow);

                $sQuery =
                "
                    INSERT INTO
                        `" . BX_SYS_PRE_VALUES_TABLE . "`
                    SET
                        `Key`      = '{$aRow['Key']}',
                        `Value`  = '{$aRow['Value']}',
                        `Order`  = '{$aRow['Order']}',
                        `LKey`   = '{$aRow['LKey']}',
                        `LKey2`  = '{$aRow['LKey2']}',
                        `LKey3`  = '{$aRow['LKey3']}',
                        `Extra`  = '{$aRow['Extra']}',
                        `Extra2` = '{$aRow['Extra2']}',
                        `Extra3` = '{$aRow['Extra3']}'
                ";

                 $this -> oMigrationModule -> _oDb -> query($sQuery);
            }
        }

        /**
         * Add new value in sys_profile_fields
         *
         * @param $sFieldName strings
         * @return void
         */
        function _transferProfileFields($sFieldName)
        {
            $sFieldName = $this -> escape($sFieldName);

            $sQuery = "SELECT * FROM `ProfileFields` WHERE `Name` = '{$sFieldName}'";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isFieldExists($aRow['Name']) ) {
                    // escape all data;
                       $aRow = $this -> escapeData($aRow);

                    // insert new
                    $sQuery =
                    "
                        INSERT INTO
                            `sys_profile_fields`
                        SET
                            `Name`             = '{$aRow['Name']}',
                            `Type`             = '{$aRow['Type']}',
                            `Control`         = '{$aRow['Control']}',
                            `Extra`         = '{$aRow['Extra']}',
                            `Min`             = '{$aRow['Min']}',
                            `Max`             = '{$aRow['Max']}',
                            `Values`         = '{$aRow['Values']}',
                            `UseLKey`         = '{$aRow['UseLKey']}',
                            `Check`         = '{$aRow['Check']}',
                            `Unique`         = '{$aRow['Unique']}',
                            `Default`         = '{$aRow['Default']}',
                            `Mandatory`     = '{$aRow['Mandatory']}',
                            `Deletable`     = '{$aRow['Deletable']}',
                            `JoinPage`      = '{$aRow['JoinPage']}',
                            `JoinBlock`     = '{$aRow['JoinBlock']}',
                            `JoinOrder`     = '{$aRow['JoinOrder']}',
                            `EditOwnBlock`  = '{$aRow['EditOwnBlock']}',
                            `EditOwnOrder`  = '{$aRow['EditOwnOrder']}',
                            `EditAdmBlock`  = '{$aRow['EditAdmBlock']}',
                            `EditAdmOrder`  = '{$aRow['EditAdmOrder']}',
                            `EditModBlock`  = '{$aRow['EditModBlock']}',
                            `EditModOrder`  = '{$aRow['EditModOrder']}',
                            `ViewMembBlock` = '{$aRow['ViewMembBlock']}',
                            `ViewMembOrder` = '{$aRow['ViewMembOrder']}',
                            `ViewAdmBlock`  = '{$aRow['ViewAdmBlock']}',
                            `ViewAdmOrder`  = '{$aRow['ViewAdmOrder']}',
                            `ViewModBlock`  = '{$aRow['ViewModBlock']}',
                            `ViewModOrder`  = '{$aRow['ViewModOrder']}',
                            `ViewVisBlock`  = '{$aRow['ViewVisBlock']}',
                            `ViewVisOrder`  = '{$aRow['ViewVisOrder']}',
                            `SearchParams`  = '{$aRow['SearchParams']}',

                            `SearchSimpleBlock`  = '{$aRow['SearchSimpleBlock']}',
                            `SearchSimpleOrder`  = '{$aRow['SearchSimpleOrder']}',
                            `SearchQuickBlock`   = '{$aRow['SearchQuickBlock']}',
                            `SearchQuickOrder`   = '{$aRow['SearchQuickOrder']}',
                            `SearchAdvBlock`     = '{$aRow['SearchAdvBlock']}',
                            `SearchAdvOrder`     = '{$aRow['SearchAdvOrder']}',
                            `MatchField`          = '{$aRow['MatchField']}',
                            `MatchPercent`          = '{$aRow['MatchPercent']}'
                    ";

                     $this -> oMigrationModule -> _oDb -> query($sQuery);
                }
            }
        }

        /**
         * Function transfer field value
         *
         * @return void
         */
        function _transformData()
        {
            // get all data from old profiles table;
            $sQuery = "SELECT * FROM `Profiles`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
               // escape all data;
               $aRow = $this -> escapeData($aRow);

                //-- update new profiles table --//
                $sUpdateField = '';
                foreach($this -> aOldProfileTable as $sKey => $aItems)
                {
                    $sValue =
                    $sUpdateField .= "`{$sKey}` = '{$aRow[$sKey]}',";
                }

                $sUpdateField = trim($sUpdateField, ',');

                $sQuery =
                "
                    UPDATE
                        `Profiles`
                    SET
                        {$sUpdateField}
                    WHERE
                        `ID` = {$aRow['ID']}
                ";

                $this -> oMigrationModule -> _oDb -> query($sQuery);
                //--
            }
        }

        /**
         * Check field exist in  sys_profile_fields
         *
         * @param $sFieldName string
         * @return true if exist;
         */
        function isFieldExists($sFieldName)
        {
            $sFieldName = $this -> escape($sFieldName);
            $sQuery  = sprintf("SELECT COUNT(*) FROM `sys_profile_fields` WHERE `Name` = '%s'", $sFieldName);
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Compile all prevalues value;
         *
         * @return boolean - true if file was compiled;
         */
        function compilePreValues()
        {
            compilePreValues();
        }
    }
