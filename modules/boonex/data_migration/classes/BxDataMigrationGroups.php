<?php

    require_once 'BxDataMigrationOrcaForum.php';

    class BxDataMigrationGroups extends BxDataMigrationOrcaForum
    {
        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationGroups(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationOrcaForum($oMigrationModule, $rOldDb, $oDolModule, true);
        }

        /**
         * Function migrate groups and groups forum data;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            mysql_query('SET NAMES utf8', $this->rOldDb);

            // transfer categories;
           $this -> setResultStatus('transfer forum categories');
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

            $sError = $this->_transferGroups();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('Groups were transferred');

            return MIGRATION_SUCCESSFUL;
        }

        function _transferGroups() {

            // transfer categories
            $rCategs = mysql_query("select * from `GroupsCateg`", $this->rOldDb);
            while ($aCateg = mysql_fetch_assoc($rCategs)) {
                $sCategNameDB = $this->escape($aCateg['Name']);

                $sCategName = $this->getOne("select `Category` from `sys_categories` where `Type` = 'bx_groups' and `Category` = '$sCategNameDB'");
                if ($sCategName) {
                    // if category already exists
                    // TODO: ...do nothing
                } else {
                    // if category doesn't exist, create it
                    $this->query("
                        insert into `sys_categories`
                            (`Category`, `Type`)
                        values
                            ('$sCategNameDB', 'bx_groups')
                    ");
                }

                // transfer groups
                $rGroups = mysql_query("select * from `Groups` where `categID` = {$aCateg['ID']}", $this->rOldDb);
                while ($aGroup = mysql_fetch_assoc($rGroups)) {
                    $sGroupNameDB = $this->escape($aGroup['Name']);

                    $iGroupID = (int)$this->getOne("select `id` from `bx_groups_main` where `title` = '$sGroupNameDB'");
                    if ($iGroupID) {
                        // if group already exists
                        // TODO: ...do nothing
                    } else {
                        // if group doesn't exist, create it
                        $g = $this->escapeData($aGroup);
                        $g['status'] = ($aGroup['status'] == 'Active') ? 'approved' : 'pending';
                        $g['created'] = $this->getTSFromDate($aGroup['created']);

                        $iFansCount = 0;
                        $rFans = mysql_query("select count(*) as `count` from `GroupsMembers` where `groupID` = {$aGroup['ID']}", $this->rOldDb);
                        if ($rFans && ($aFans = mysql_fetch_assoc($rFans))) {
                            $iFansCount = $aFans['count'];
                            mysql_free_result($rFans);
                        }

                        $this->query("
                            insert into `bx_groups_main`
                                (     `id`,         `title`,        `uri`,         `desc`,         `country`,         `city`,         `status`,         `created`,         `author_id`,    `categories`,   `fans_count`,   `allow_view_group_to`, `allow_view_fans_to`, `allow_comment_to`, `allow_rate_to`, `allow_post_in_forum_to`, `allow_join_to`, `join_confirmation`, `allow_upload_photos_to`, `allow_upload_videos_to`, `allow_upload_sounds_to`, `allow_upload_files_to`) values
                                ('{$g['ID']}', '{$g['Name']}', '{$g['Uri']}', '{$g['Desc']}', '{$g['Country']}', '{$g['City']}', '{$g['status']}', '{$g['created']}', '{$g['creatorID']}', '$sCategNameDB', '$iFansCount', '3',                   '3',                  'f',                'f',            'f',                       '3',             '0',                 'a',                      'a',                      'a',                      'a')
                        ");

                        $iGroupID = $this->lastId();
                    }

                    // transfer profiles
                    $rProfiles = mysql_query("select * from `GroupsMembers` where `groupID` = {$aGroup['ID']}", $this->rOldDb);
                    while ($p = mysql_fetch_assoc($rProfiles)) {
                        $p['Date'] = $this->getTSFromDate($p['Date']);
                        $p['confirmed'] = ($p['status'] == 'Active') ? 1 : 0;

                        $this->query("
                            insert into `bx_groups_fans`
                            (    `id_entry`,      `id_profile`,     `when`,       `confirmed`) values
                            ({$p['groupID']}, {$p['memberID']}, {$p['Date']}, {$p['confirmed']})
                        ");
                    }

                    // transfer gallery
                    $rImages = mysql_query("select * from `GroupsGallery` where `groupID` = {$aGroup['ID']}", $this->rOldDb);
                    while ($i = mysql_fetch_assoc($rImages)) {
                        $aFileInfo = array(
                            'medTitle' => $aGroup['Name'],
                            'medDesc' => $aGroup['Name'],
                            'medTags' => 'groups',
                            'Categories' => array('Groups'),
                        );
                        $sOrigFile = $this->oMigrationModule->_oDb->getExtraParam('config_root') . 'groups/gallery/' . "{$aGroup['ID']}_{$i['ID']}_{$i['seed']}.{$i['ext']}";
                        $aPathInfo = pathinfo ($sOrigFile);
                        $sTmpFile = BX_DIRECTORY_PATH_ROOT . 'tmp/bx_migration_groups_image.' . $aPathInfo['extension'];
                        @copy ($sOrigFile, $sTmpFile);
                        $iPhotoId = BxDolService::call('photos', 'perform_photo_upload', array($sTmpFile, $aFileInfo, false, $i['by']), 'Uploader');
                        @unlink($sTmpFile);
                        if ($iPhotoId) {
                            if ($this->query("INSERT INTO `bx_groups_images` VALUES ($iGroupID, $iPhotoId)")) {
                                $this->query("UPDATE `bx_groups_main` SET `thumb` = '$iPhotoId' WHERE `id` = '$iGroupID' AND `thumb` = 0");
                            }
                        }
                    }

                    // transfer forum
                    //TODO: ...
                }
            }
        }

        function getTSFromDate($sDateTime) {
            list($sDate, $sTime) = explode(' ', $sDateTime);
            $sTime = strlen($sTime) ? $sTime : '0:0:0';

            $aDate = explode('-', $sDate);
            $aTime = explode(':', $sTime);

            return mktime((int)$aTime[0], (int)$aTime[1], (int)$aTime[2], (int)$aDate[1], (int)$aDate[2], (int)$aDate[0]);
        }
    }
