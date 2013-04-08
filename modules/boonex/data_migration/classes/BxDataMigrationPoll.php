<?php

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationPoll extends BxDataMigrationData
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
        function BxDataMigrationPoll(&$oMigrationModule, &$rOldDb, $oDolModule = '')
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

            // set new status;
            $this -> setResultStatus('Polls transfer now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            // transfer all profiles polls;
            $sError = $this -> transferredProfilePolls();
            if(!$sError) {
                // transfer all admins polls;
                $sError = $this -> transferredAdminPolls();
                if($sError) {
                    $this -> setResultStatus($sError);
                    return MIGRATION_FAILED;
                }
            }
            else {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('All polls were transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * Function will trasnfer all admin's polls;
         *
         * @return : (string) - error message or empty;
         */
        function transferredAdminPolls()
        {
            // set charset;
            /*$sQuery = "ALTER TABLE `polls_q` CHANGE `Question` `Question` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
            mysql_query($sQuery, $this->rOldDb);

            // set charset;
            $sQuery = "ALTER TABLE `polls_a` CHANGE `Answer` `Answer` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
            mysql_query($sQuery, $this->rOldDb);
*/
            $sQuery = "SELECT * FROM `polls_q`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

             while( $aRow = mysql_fetch_assoc($rResult) )
             {
                     $sPollQuestion    = $this -> oMigrationModule -> _oDb  -> escape($aRow['Question']);
                     $sPollResult      = $sPollAnswers   = '';
                     $iPollTotalresult = 0;
                     $sPollStatus      = $aRow['Active'] == 'on' ? 'active' : '';

                     // get poll's answer list;
                     $sQuery  = "SELECT * FROM `polls_a` WHERE `ID` = {$aRow['ID']}";
                     $rResult2 = mysql_query($sQuery, $this -> rOldDb);

                     // get answer list;
                     while( $aRow2 = mysql_fetch_assoc($rResult2) )
                     {
                         $sPollAnswers     .= $aRow2['Answer'] . '<delim>';
                         $sPollResult      .= $aRow2['Votes']  . ';';
                         $iPollTotalresult += $aRow2['Votes'];
                     }

                     $sPollAnswers = $this -> oMigrationModule -> _oDb  -> escape($sPollAnswers);

                     $sQuery =
                      "
                            INSERT INTO
                                `{$this -> oDolModule -> _oDb -> sTablePrefix}data`
                            SET
                                `poll_question` = '{$sPollQuestion}',
                                   `poll_answers`  = '{$sPollAnswers}',
                                   `poll_results`  = '{$sPollResult}',
                                   `poll_status`    = '{$sPollStatus }',
                                   `poll_approval` = '1',
                                   `poll_date` = UNIX_TIMESTAMP(),
                                   `poll_total_votes` = '{$iPollTotalresult}',
                                   `id_profile`       = 0
                      ";

                      $this -> oMigrationModule -> _oDb -> query($sQuery);
                      $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                      if($iResult <= 0) {
                          return 'Database error. Cannot insert new admin poll in the database.';
                      }

                      $this -> iTransffered++;
            }
        }

        /**
         * Function will transfer all profile's polls;
         *
         * @return : (string) - error message or empty;
         */
        function transferredProfilePolls()
        {
             // set charset;
           /* $sQuery = "ALTER TABLE `ProfilesPolls` CHANGE `poll_question` `poll_question` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
            mysql_query($sQuery, $this->rOldDb);

            // set charset;
            $sQuery = "ALTER TABLE `ProfilesPolls` CHANGE `poll_answers` `poll_answers` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
            mysql_query($sQuery, $this->rOldDb);
*/
            $sQuery  = "SELECT * FROM `ProfilesPolls`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while($aRow = mysql_fetch_assoc($rResult))
            {
                if( !$this -> isPollExisting($aRow['id_poll']) ) {
                    $sPollQuestion = $this -> oMigrationModule -> _oDb  -> escape($aRow['poll_question']);
                    $sPollAnswers  = $this -> oMigrationModule -> _oDb  -> escape($aRow['poll_answers']);

                    // execute query;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> oDolModule -> _oDb -> sTablePrefix}data`
                        SET
                            `id_poll`        = {$aRow['id_poll']},
                            `poll_question` = '{$sPollQuestion}',
                               `poll_answers`  = '{$sPollAnswers}',
                               `poll_results`  = '{$aRow['poll_results']}',
                               `poll_status`    = '{$aRow['poll_status']}',
                               `poll_approval` = '{$aRow['poll_approval']}',
                               `poll_date` = UNIX_TIMESTAMP(),
                               `poll_total_votes` = '{$aRow['poll_total_votes']}',
                               `id_profile`       = {$aRow['id_profile']}
                   ";

                   $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                   if($iResult <= 0) {
                        return 'Database error. Cannot insert new profile poll in the database.';
                   }

                   $this -> iTransffered++;
                }
                else {
                    return 'Duplicate data (in profiles poll).
                        Polls with
                        similar info already exists
                        (remove all polls and start again)';
                }
            }
        }

         /**
         * Function will check existing poll in module;
         *
         * @param  : $iPollId (integer) - poll's Id;
         * @return : true if exist;
         */
        function isPollExisting($iPollId)
        {
            $iPollId = (int) $iPollId;
            $sQuery  = "SELECT COUNT(*) FROM `{$this -> oDolModule -> _oDb -> sTablePrefix}data` WHERE `id_poll` = '{$iPollId}'";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }
    }