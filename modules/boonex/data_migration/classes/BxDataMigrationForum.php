<?php

    require_once 'BxDataMigrationOrcaForum.php';

    class BxDataMigrationForum extends BxDataMigrationOrcaForum
    {
        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationForum(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationOrcaForum($oMigrationModule, $rOldDb, $oDolModule);
        }

        /**
         * Function migrate polls data;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            mysql_query('SET NAMES utf8', $this->rOldDb);

            // sample cat and forum is created in clean instllation, detele it before migration
            $this->emptyDatabase ();

            // transfer categories;
           $this -> setResultStatus('transfer categories');
           $sError =  $this -> _transferCategories();
           if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forums;
            $this -> setResultStatus('transfer forums');
            $sError =  $this -> _transferForums();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer flags;
            $this -> setResultStatus('transfer forum\'s flags');
            $sError =  $this -> _transferFlags();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's posts;
            $this -> setResultStatus('transfer forum\'s posts');
            $sError =  $this -> _transferPosts();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's topics;
            $this -> setResultStatus('transfer forum\'s topics');
            $sError =  $this -> _transferTopics();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's users;
            $this -> setResultStatus('transfer forum\'s users');
            $sError =  $this -> _transferUsers();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's users activity;
            $this -> setResultStatus('transfer forum\'s users activity');
            $sError =  $this -> _transferUsersActivity();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's users stat;
            $this -> setResultStatus('transfer forum\'s users stat');
            $sError =  $this -> _transferUsersStat();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            //transfer forum's users vote;
            $this -> setResultStatus('transfer forum\'s users vote');
            $sError =  $this -> _transferUsersVote();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('Forums were transferred');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * sample cat and forum is created in clean instllation, detele it before migration
         */
        function emptyDatabase () {

            $sQuery = "DELETE FROM `{$this -> sNewTablePrefix}forum_cat` WHERE `cat_uri` = 'General'";
            $this -> oMigrationModule -> _oDb -> query($sQuery);

            $sQuery = "DELETE FROM `{$this -> sNewTablePrefix}forum` WHERE `forum_uri` = 'General-discussions'";
            $this -> oMigrationModule -> _oDb -> query($sQuery);
        }

    }
