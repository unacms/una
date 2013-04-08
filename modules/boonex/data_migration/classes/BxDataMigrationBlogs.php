<?php

require_once 'BxDataMigrationData.php';

class BxDataMigrationBlogs extends BxDataMigrationData {
    var $iTransffered = 0;
    var $iPostsTransffered = 0;

    /**
     * Class constructor;
     *
     * @param  : $oMigrationModule (object) - object instance of migration class;
     * @param  : $rOldDb (resourse) - connect to old dolphin's database;
     * @param  : $oDolModule (object);
     * @return : void;
     */
    function BxDataMigrationBlogs(&$oMigrationModule, &$rOldDb, $oDolModule = '') {
        parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
    }

    /**
     * Function migrate blogs data;
     *
     * @return : (integer) operation result;
     */
    function getMigration() {
        // set new status;
        $this -> setResultStatus('All blogs transfer now');

         mysql_query('SET NAMES utf8', $this->rOldDb);

        // transfer all profiles blogs, categories and posts;
        $this -> transferProfileBlogs();

         // set as finished;
         $this -> setResultStatus('All blogs were transferred (' .  $this -> iTransffered . ' blogs, ' .  $this -> iPostsTransffered . ' posts)');

        return MIGRATION_SUCCESSFUL;
    }

    /**
     * Function will transfer all profile's blogs, categories and posts;
     *
     * @return : void;
     */
    function transferProfileBlogs() {
        $sTruncateSQL1 = "TRUNCATE TABLE `bx_blogs_cmts`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL1);
        $sTruncateSQL2 = "TRUNCATE TABLE `bx_blogs_main`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL2);
        $sTruncateSQL3 = "TRUNCATE TABLE `bx_blogs_posts`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL3);
        $sTruncateSQL4 = "DELETE FROM `sys_categories` WHERE `Type` = 'bx_blogs' AND `Owner` > 0";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL4);

        $sSrcPath = $this -> oMigrationModule -> _oDb -> getExtraParam('config_mediaImages') . 'blog/';
        $sDstPath = BX_DIRECTORY_PATH_ROOT . "media/images/blog/";

        $aPosts = array();
        $aCategories = array();

        $sQuery  = "SELECT * FROM `Blogs` ORDER BY `OwnerID` ASC";
        $rResult = mysql_query($sQuery, $this -> rOldDb);
        while($aRow = mysql_fetch_assoc($rResult)) {
            $iBlogID = (int)$aRow['ID'];
            $iBlogOwnerID = (int)$aRow['OwnerID'];
            $sBlogDescription = $this -> oMigrationModule -> _oDb  -> escape($aRow['Description']);

            $sCatSQL  = "SELECT * FROM `BlogCategories` WHERE `OwnerID`='{$iBlogOwnerID}' ORDER BY `BlogCategories`.`CategoryID` ASC";

            $rCatRes = mysql_query($sCatSQL, $this -> rOldDb);
            while($aCatRow = mysql_fetch_assoc($rCatRes)) {
                $iCatID = (int)$aCatRow['CategoryID'];

                if (! array_key_exists($iCatID, $aCategories)) {
                    $aCategories[$iCatID] = $iCatID;
                    $sCatName = $aCatRow['CategoryName'];

                    //-- eSASe modification --//
                    if($sCatName == 'Uncategorized') {
                        $sCatName = '';
                    }

                    if($sCatName && $this -> isCategoryExist('bx_blogs', $sCatName) ) {
                            $sCatName = '';
                    }
                    //--

                    $sCatName = $this -> oMigrationModule -> _oDb  -> escape($sCatName);

                    $sPostsSQL = "SELECT *, UNIX_TIMESTAMP(`PostDate`) AS 'Date_UTS' FROM `BlogPosts` WHERE `CategoryID`='{$iCatID}' ORDER BY `BlogPosts`.`CategoryID` ASC";
                    $rPostsRes = mysql_query($sPostsSQL, $this -> rOldDb);

                    if (mysql_num_rows($rPostsRes)) {
                        while($aPostsRow = mysql_fetch_assoc($rPostsRes)) {
                            $iPostID = (int)$aPostsRow['PostID'];

                            if (! array_key_exists($iPostID, $aPosts)) {
                                $aPosts[$iPostID] = $iPostID;

                                $iPostDateUTS = (int)$aPostsRow['Date_UTS'];
                                $sPostCaption = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['PostCaption']);
                                $sPostUri = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['PostUri']);
                                $sPostText = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['PostText']);
                                $sPostStatus = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['PostStatus']);
                                $sPostPhoto = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['PostPhoto']);
                                $sPostTags = $this -> oMigrationModule -> _oDb -> escape($aPostsRow['Tags']);
                                $iPostDateUTS = (int)$aPostsRow['Date_UTS'];
                                $iAllowView = ($aPostsRow['PostReadPermission'] == 'public') ? 3 : 5;
                                $iAllowComment = ($aPostsRow['PostCommentPermission'] == 'public') ? 3 : 5;

                                $sPostCommentsCntSQL  = "SELECT COUNT(*) FROM `CmtsBlogPosts` WHERE `cmt_object_id`='{$iPostID}'";
                                $rPostCommentsCntRes = mysql_query($sPostCommentsCntSQL, $this -> rOldDb);
                                $aPostCommentsCnt = mysql_fetch_array($rPostCommentsCntRes);
                                $iPostCommentsCnt = (int)$aPostCommentsCnt[0];

                                // adding posts
                                $sPostQuery =
                                "
                                    INSERT INTO
                                        `bx_blogs_posts`
                                    SET
                                        `PostID` = '{$iPostID}',
                                        `PostCaption` = '{$sPostCaption}',
                                        `PostUri` = '{$sPostUri}',
                                        `PostText` = '{$sPostText}',
                                        `PostDate` = '{$iPostDateUTS}',
                                        `PostStatus` = '{$sPostStatus}',
                                        `PostPhoto` = '{$sPostPhoto}',
                                        `Tags` = '{$sPostTags}',
                                        `Featured` = 0,
                                        `Views` = 0,
                                        `Rate` = 0,
                                        `RateCount` = 0,
                                        `CommentsCount` = {$iPostCommentsCnt},
                                        `OwnerID` = '{$iBlogOwnerID}',
                                        `Categories` = '{$sCatName}',
                                        `allowView` = {$iAllowView},
                                        `allowRate` = 3,
                                        `allowComment` = {$iAllowComment}
                               ";
                               $this -> oMigrationModule -> _oDb -> query($sPostQuery);
                               $this -> iPostsTransffered++;

                                // TODO images
                                @copy($sSrcPath . 'big_' . $sPostPhoto, $sDstPath . '_big_' . $sPostPhoto);

                                imageResize($sDstPath . '_big_' . $sPostPhoto, $sDstPath . 'small_' . $sPostPhoto, $this -> oDolModule->iIconSize, $this -> oDolModule->iIconSize);
                                imageResize($sDstPath . '_big_' . $sPostPhoto, $sDstPath . 'big_' . $sPostPhoto, $this -> oDolModule->iThumbSize, $this -> oDolModule->iThumbSize);
                                imageResize($sDstPath . '_big_' . $sPostPhoto, $sDstPath . 'orig_' . $sPostPhoto, $this -> oDolModule->iImgSize, $this -> oDolModule->iImgSize);

                                @unlink ($sDstPath . '_big_' . $sPostPhoto);

                                // adding custom categories (with >0 posts inside)
                                if($sCatName) {
                                    $sCatQuery =
                                    "
                                        INSERT INTO
                                            `sys_categories`
                                        SET
                                            `Type` = 'bx_blogs',
                                            `Category` = '{$sCatName}',
                                            `Owner` = '{$iBlogOwnerID}',
                                            `ID` = '{$iPostID}'
                                   ";

                                   $this -> oMigrationModule -> _oDb -> query($sCatQuery);
                                }
                            }
                        }
                    } else {
                        // adding custom categories (with 0 posts inside)
                        if($sCatName) {
                            $sCatQuery =
                            "
                                INSERT INTO
                                    `sys_categories`
                                SET
                                    `Type` = 'bx_blogs',
                                    `Category` = '{$sCatName}',
                                    `Owner` = '{$iBlogOwnerID}'
                           ";

                            $this -> oMigrationModule -> _oDb -> query($sCatQuery);
                        }
                    }
                }
            }

            // adding blogs
            $sBlogQuery =
            "
                INSERT INTO
                    `bx_blogs_main`
                SET
                    `ID` = {$iBlogID},
                    `OwnerID` = '{$iBlogOwnerID}',
                    `Description` = '{$sBlogDescription}'
           ";
           $this -> oMigrationModule -> _oDb -> query($sBlogQuery);
           $this -> iTransffered++;
        }

        // moving comments
        $sCommQuery = "SELECT * FROM `CmtsBlogPosts`";
        $rCommResult = mysql_query($sCommQuery, $this -> rOldDb);
        while($aCommRow = mysql_fetch_assoc($rCommResult)) {
            $iCmtID = (int)$aCommRow['cmt_id'];
            $iCmtParentID = (int)$aCommRow['cmt_parent_id'];
            $iCmtObjectID = (int)$aCommRow['cmt_object_id'];
            $iCmtAuthorID = (int)$aCommRow['cmt_author_id'];
            $sCmtText = $this -> oMigrationModule -> _oDb -> escape($aCommRow['cmt_text']);
            $iCmtRate = (int)$aCommRow['cmt_rate'];
            $iCmtRateCnt = (int)$aCommRow['cmt_rate_count'];
            $sCmtTime = $aCommRow['cmt_time'];
            $sCmtReplies = (int)$aCommRow['cmt_replies'];

            $sCommSQL = "
                INSERT INTO `bx_blogs_cmts` (`cmt_id`, `cmt_parent_id`, `cmt_object_id`, `cmt_author_id`, `cmt_text`, `cmt_mood`, `cmt_rate`, `cmt_rate_count`, `cmt_time`, `cmt_replies`) VALUES ({$iCmtID}, {$iCmtParentID}, {$iCmtObjectID}, {$iCmtAuthorID}, '{$sCmtText}', 0, {$iCmtRate}, {$iCmtRateCnt}, '{$sCmtTime}', {$sCmtReplies});
            ";
            $this -> oMigrationModule -> _oDb -> query($sCommSQL);

        }
    }

    /**
     * Check category;
     *
     * @return booelan - true if exist;
     */
    function isCategoryExist($sType, $sCategory)
    {
        $sCategory = $this -> oMigrationModule -> _oDb  -> escape($sCategory);
        $sType = $this -> oMigrationModule -> _oDb  -> escape($sType);

        $sQuery = "SELECT COUNT(*) FROM `sys_categories` WHERE `Type` = '{$sType}' AND `Category` = '{$sCategory}'";
        return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
    }
}

?>