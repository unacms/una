<?php

    require_once 'BxDataMigrationData.php';
    //-- this is abstract class, need to inherit --//
    class BxDataMigrationOrcaForum extends BxDataMigrationData
    {
        var $isGroupForum;
        var $sOldTablePrefix;
        var $sNewTablePrefix;

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationOrcaForum(&$oMigrationModule, &$rOldDb, $oDolModule = '', $isGroupForum = false)
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
            $this -> isGroupForum = $isGroupForum;

            $this -> sOldTablePrefix  = !$this -> isGroupForum ? 'pre_' : 'grp_' ;
            $this -> sNewTablePrefix  = !$this -> isGroupForum ? 'bx_' : 'bx_groups_' ;
        }

        //-- private methods --//

        /**
         * Transfer all forum's categories;
         *
         * @return : (string) - error message or empty;
         */
        function _transferCategories()
        {
            if($this -> isGroupForum) {
                // clear all data;
                $sQuery = "TRUNCATE TABLE `{$this -> sNewTablePrefix}forum_cat`";
                $this -> oMigrationModule -> _oDb -> query($sQuery);
            }

            // get all categories;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_cat`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isCategoryExisting($aRow['cat_id']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_cat`
                        SET
                            `cat_id`    = '{$aRow['cat_id']}',
                            `cat_uri`   = '{$aRow['cat_uri']}',
                            `cat_name`  = '{$aRow['cat_name']}',
                            `cat_icon`  = '{$aRow['cat_icon']}',
                            `cat_order` = '{$aRow['cat_order']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new forum\'s category in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum categories).
                            Remove all data from forum and start again';
                }
            }
        }

         /**
         * Transfer all forums;
         *
         * @return : (string) - error message or empty;
         */
        function _transferForums()
        {
            // get all forums;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            $sExtraSql = '';
            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isForumExisting($aRow['forum_id']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    if ($this -> isGroupForum) {
                        $sExtraSql = ", `entry_id` = '{$aRow['forum_id']}'";
                    }

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum`
                        SET
                            `forum_id`     = '{$aRow['forum_id']}',
                            `forum_uri`    = '{$aRow['forum_uri']}',
                            `cat_id`       = '{$aRow['cat_id']}',
                            `forum_title`  = '{$aRow['forum_title']}',
                            `forum_desc`   = '{$aRow['forum_desc']}',
                            `forum_posts`  = '{$aRow['forum_posts']}',
                            `forum_topics` = '{$aRow['forum_topics']}',
                            `forum_last`   = '{$aRow['forum_last']}',
                            `forum_type`   = '{$aRow['forum_type']}'
                            {$sExtraSql}
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new forum in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum).
                            Remove all data from forum and start again';
                }
            }
        }

         /**
         * Transfer all forum's flags;
         *
         * @return : (string) - error message or empty;
         */
        function _transferFlags()
        {
            // get all flags;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_flag`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isFlagExisting($aRow['user'], $aRow['topic_id']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_flag`
                        SET
                            `user`     = '{$aRow['user']}',
                            `topic_id` = '{$aRow['topic_id']}',
                            `when`     = '{$aRow['when']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new forum\'s flag in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum flags).
                            Remove all data from forum and start again';
                }
            }
        }

        /**
         * Transfer all forum's posts;
         *
         * @return : (string) - error message or empty;
         */
        function _transferPosts()
        {
            // get all posts;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_post`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isPostExisting($aRow['post_id']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_post`
                        SET
                            `post_id`   = '{$aRow['post_id']}',
                            `topic_id`  = '{$aRow['topic_id']}',
                            `forum_id`  = '{$aRow['forum_id']}',
                            `user`      = '{$aRow['user']}',
                            `post_text` = '{$aRow['post_text']}',
                            `when`          = '{$aRow['when']}',
                            `votes`     = '{$aRow['votes']}',
                            `reports`   = '{$aRow['reports']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new forum\'s post in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum posts).
                            Remove all data from forum and start again';
                }
            }
        }

         /**
         * Transfer all forum's topics;
         *
         * @return : (string) - error message or empty;
         */
        function _transferTopics()
        {
            // get all topics;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_topic`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isTopicExisting($aRow['topic_id']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_topic`
                        SET
                            `topic_id`          = '{$aRow['topic_id']}',
                            `topic_uri`         = '{$aRow['topic_uri']}',
                            `forum_id`          = '{$aRow['forum_id']}',
                            `topic_title`       = '{$aRow['topic_title']}',
                            `when`              = '{$aRow['when']}',
                            `topic_posts`        = '{$aRow['topic_posts']}',
                            `first_post_user` = '{$aRow['first_post_user']}',
                            `first_post_when` = '{$aRow['first_post_when']}',
                            `last_post_user`  = '{$aRow['last_post_user']}',
                            `last_post_when`  = '{$aRow['last_post_when']}',
                            `topic_sticky`    = '{$aRow['topic_sticky']}',
                            `topic_locked`       = '{$aRow['topic_locked']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new forum\'s topic in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum topics).
                            Remove all data from forum and start again';
                }
            }
        }

        /**
         * Transfer all forum's users;
         *
         * @return : (string) - error message or empty;
         */
        function _transferUsers()
        {
            // get all users;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_user`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isUserExisting($aRow['user_name']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_user`
                        SET
                            `user_name`          = '{$aRow['user_name']}',
                            `user_pwd`         = '{$aRow['user_pwd']}',
                            `user_email`      = '{$aRow['user_email']}',
                            `user_join_date`  = '{$aRow['user_join_date']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new user in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum users).
                            Remove all data from forum and start again';
                }
            }
        }

        /**
         * Transfer all forum's users activity;
         *
         * @return : (string) - error message or empty;
         */
        function _transferUsersActivity()
        {
            // clear all data;
            $sQuery = "TRUNCATE TABLE `{$this -> sNewTablePrefix}forum_user_activity`";
            $this -> oMigrationModule -> _oDb -> query($sQuery);

            // get all users activity;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_user_activity`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                // escape all data;
                $aRow = $this -> escapeData($aRow);

                // insert new;
                $sQuery =
                "
                    INSERT INTO
                        `{$this -> sNewTablePrefix}forum_user_activity`
                    SET
                        `user`           = '{$aRow['user']}',
                        `act_current`  = '{$aRow['act_current']}',
                        `act_last`     = '{$aRow['act_last']}'
                ";

                $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                if($iResult <= 0) {
                    return 'Database error. Cannot insert new user activity in the database.';
                }
            }
        }

        /**
         * Transfer all forum's users stat;
         *
         * @return : (string) - error message or empty;
         */
        function _transferUsersStat()
        {
            // clear all data;
            $sQuery = "TRUNCATE TABLE `{$this -> sNewTablePrefix}forum_user_stat`";
            $this -> oMigrationModule -> _oDb -> query($sQuery);

            // get all users stat;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_user_stat`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                // escape all data;
                $aRow = $this -> escapeData($aRow);

                // insert new;
                $sQuery =
                "
                    INSERT INTO
                        `{$this -> sNewTablePrefix}forum_user_stat`
                    SET
                        `user`             = '{$aRow['user']}',
                        `posts`           = '{$aRow['posts']}',
                        `user_last_post` = '{$aRow['user_last_post']}'
                ";

                $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                if($iResult <= 0) {
                    return 'Database error. Cannot insert new user stat in the database.';
                }
            }
        }

        /**
         * Transfer all forum's users vote;
         *
         * @return : (string) - error message or empty;
         */
        function _transferUsersVote()
        {
            // get all users vote;
            $sQuery = "SELECT * FROM `{$this -> sOldTablePrefix}forum_vote`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
               if( !$this -> isUserVoteExisting($aRow['user']) ) {
                       // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    // insert new;
                    $sQuery =
                    "
                        INSERT INTO
                            `{$this -> sNewTablePrefix}forum_vote`
                        SET
                            `user_name`     = '{$aRow['user_name']}',
                            `post_id`          = '{$aRow['post_id']}',
                            `vote_when`     = '{$aRow['vote_when']}',
                            `vote_point`     = '{$aRow['vote_point']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert new user vote in the database.';
                    }
                }
                else {
                    return 'Duplicate data (in forum users vote).
                            Remove all data from forum and start again';
                }
            }
        }

        //-- public methods --//

        /**
         * Check category existing;
         *
         * @param  : $iCatId integer - category id;
         * @return : boolean true if exist;
         */
        function isCategoryExisting($iCatId)
        {
            $iCatId = (int) $iCatId;
            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_cat` WHERE `cat_id` = {$iCatId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check forum existing;
         *
         * @param  : $iForumId integer - forum id;
         * @return : boolean true if exist;
         */
        function isForumExisting($iForumId)
        {
            $iForumId = (int) $iForumId;
            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum` WHERE `forum_id` = {$iForumId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check flags existing;
         *
         * @param  : $iUserId string - user nickname;
         * @param  : $iTopicId integer - topic id;
         * @return : boolean true if exist;
         */
        function isFlagExisting($sUserName, $iTopicId)
        {
            $sUserName = $this -> oMigrationModule -> _oDb  -> escape($sUserName);
            $iTopicId = (int) $iTopicId;

            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_flag` WHERE `user` = '{$sUserName}' AND `topic_id` = {$iTopicId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check post existing;
         *
         * @param  : $iPostId integer - post id;
         * @return : boolean true if exist;
         */
        function isPostExisting($iPostId)
        {
            $iPostId = (int) $iPostId;

            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_post` WHERE `post_id` = {$iPostId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check topic existing;
         *
         * @param  : $iTopicId integer - topic id;
         * @return : boolean true if exist;
         */
        function isTopicExisting($iTopicId)
        {
            $iTopicId = (int) $iTopicId;

            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_topic` WHERE `topic_id` = {$iTopicId}";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check user existing;
         *
         * @param  : $sUserName string;
         * @return : boolean true if exist;
         */
        function isUserExisting($sUserName)
        {
            $sUserName = $this -> oMigrationModule -> _oDb  -> escape($sUserName);

            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_user` WHERE `user_name` = '{$sUserName}'";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check user vote;
         *
         * @param  : $sUserName string;
         * @return : boolean true if exist;
         */
        function isUserVoteExisting($sUserName)
        {
            $sUserName = $this -> oMigrationModule -> _oDb  -> escape($sUserName);

            $sQuery  = "SELECT COUNT(*) FROM `{$this -> sNewTablePrefix}forum_vote` WHERE `user_name` = '{$sUserName}'";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }
    }
