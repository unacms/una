<?php

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationNews extends BxDataMigrationData
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
        function BxDataMigrationNews(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        }

        /**
         * Function migrate polls data;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            if(!$this -> oDolModule) {
                 $this -> setResultStatus('System error: object  instance is not received');
                 return MIGRATION_FAILED;
            }

             mysql_query('SET NAMES utf8', $this->rOldDb);

            // set new status;
            $this -> setResultStatus('News transfer now');

            $sQuery = "SELECT * FROM `News`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);
            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isNewsExisting($aRow['NewsUri'], $aRow['ID']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    $sNewsContent = $aRow['Snippet'] . ' ' . $aRow['Text'];
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> oDolModule -> _oDb -> _sPrefix}entries`
                        SET
                            `id` =  {$aRow['ID']},
                            `date` = UNIX_TIMESTAMP('{$aRow['Date']}'),
                            `caption` = '{$aRow['Header']}',
                            `content` = '{$sNewsContent}',
                            `uri` = '{$aRow['NewsUri']}',
                            `categories` = 'news'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        $this -> setResultStatus('Database error. Cannot insert new news in the database.');
                        return MIGRATION_FAILED;
                    }

                    $this -> iTransffered++;
                }
                else {
                    $this -> setResultStatus('Duplicate data. News with similar info already exists (remove all news and start again)');
                    return MIGRATION_FAILED;
                }
            }

            // set as finished;
            $this -> setResultStatus('All news were transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * Function check existing news in module;
         *
         * @param  : $sNewsHeader (string) - news header text;
         * @param  : $iNewsId (integer) - news id;
         * @return : true if exist;
         */
        function isNewsExisting($sNewsUri, $iNewsId)
        {
            $iNewsId = (int) $iNewsId;
            $sNewsUri = $this -> oMigrationModule -> _oDb  -> escape($sNewsUri);
            $sQuery  = "SELECT COUNT(*) FROM `{$this -> oDolModule -> _oDb -> _sPrefix}entries` WHERE `uri` = '{$sNewsUri}' OR `id` = {$iNewsId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }
    }