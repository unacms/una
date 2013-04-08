<?php

require_once 'BxDataMigrationData.php';

class BxDataMigrationAds extends BxDataMigrationData {
    var $iTransffered = 0;

    /**
     * Class constructor;
     *
     * @param  : $oMigrationModule (object) - object instance of migration class;
     * @param  : $rOldDb (resourse) - connect to old dolphin's database;
     * @param  : $oDolModule (object);
     * @return : void;
     */
    function BxDataMigrationAds(&$oMigrationModule, &$rOldDb, $oDolModule = '') {
         parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
    }

    /**
     * Function will migrate data;
     *
     * @return : (integer) operation result;
     */
    function getMigration() {
        if(!$this -> oDolModule) {
             $this -> setResultStatus('System error: object instance of Ads is not received');
             return MIGRATION_FAILED;
        }

        // set new status;
        $this -> setResultStatus('Ads transfer now');

         mysql_query('SET NAMES utf8', $this->rOldDb);

        // transfer all profiles ads and categories;
        $sError = $this -> transferProfileAds();
        if($sError) {
            $this -> setResultStatus($sError);
            return MIGRATION_FAILED;
        }

        // set as finished;
        $this -> setResultStatus('All Ads were transferred (' .  $this -> iTransffered . ' items)');

        return MIGRATION_SUCCESSFUL;
    }

    /**
     * Function will transfer all profile's ads and categories;
     *
     * @return : void;
     */
    function transferProfileAds() {
        $sTruncateSQL1 = "TRUNCATE TABLE `bx_ads_main`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL1);
        $sTruncateSQL2 = "TRUNCATE TABLE `bx_ads_cmts`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL2);
        $sTruncateSQL3 = "TRUNCATE TABLE `bx_ads_main_media`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL3);
        $sTruncateSQL4 = "TRUNCATE TABLE `bx_ads_category`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL4);
        $sTruncateSQL5 = "TRUNCATE TABLE `bx_ads_category_subs`";
        $this -> oMigrationModule -> _oDb -> query($sTruncateSQL5);

        // categories
        $sQuery  = "SELECT * FROM `Classifieds`";
        $rResult = mysql_query($sQuery, $this -> rOldDb);
        while($aRow = mysql_fetch_assoc($rResult)) {
            $iCatID = (int)$aRow['ID'];
            $sCatName = $this -> oMigrationModule -> _oDb  -> escape($aRow['Name']);
            $sCatUri = $this -> oMigrationModule -> _oDb  -> escape($aRow['CEntryUri']);
            $sCatDescription = $this -> oMigrationModule -> _oDb  -> escape($aRow['Description']);
            $sCatCustomFieldName1 = $this -> oMigrationModule -> _oDb  -> escape($aRow['CustomFieldName1']);
            $sCatCustomFieldName2 = $this -> oMigrationModule -> _oDb  -> escape($aRow['CustomFieldName2']);
            $sCatUnit = $this -> oMigrationModule -> _oDb  -> escape($aRow['Unit']);

            $sAddCatQuery =
            "
                INSERT INTO
                    `bx_ads_category`
                SET
                    `ID` = '{$iCatID}',
                    `Name` = '{$sCatName}',
                    `CEntryUri` = '{$sCatUri}',
                    `Description` = '{$sCatDescription}',
                    `CustomFieldName1` = '{$sCatCustomFieldName1}',
                    `CustomFieldName2` = '{$sCatCustomFieldName2}',
                    `Unit1` = '{$sCatUnit}',
                    `Unit2` = '{$sCatUnit}',
                    `Picture` = 'bx_ads.png'
           ";
           $this -> oMigrationModule -> _oDb -> query($sAddCatQuery);
        }

        // subcategories
        $sSubQuery  = "SELECT * FROM `ClassifiedsSubs`";
        $rSubResult = mysql_query($sSubQuery, $this -> rOldDb);
        while($aSubRow = mysql_fetch_assoc($rSubResult)) {
            $iSubCatID = (int)$aSubRow['ID'];
            $iSubCatParentID = (int)$aSubRow['IDClassified'];
            $sSubCatName = $this -> oMigrationModule -> _oDb  -> escape($aSubRow['NameSub']);
            $sSubCatUri = $this -> oMigrationModule -> _oDb  -> escape($aSubRow['SEntryUri']);
            $sSubCatDescription = $this -> oMigrationModule -> _oDb  -> escape($aSubRow['Description']);

            $sAddSubCatQuery =
            "
                INSERT INTO
                    `bx_ads_category_subs`
                SET
                    `ID` = '{$iSubCatID}',
                    `IDClassified` = '{$iSubCatParentID}',
                    `NameSub` = '{$sSubCatName}',
                    `SEntryUri` = '{$sSubCatUri}',
                    `Description` = '{$sSubCatDescription}'
           ";
           $this -> oMigrationModule -> _oDb -> query($sAddSubCatQuery);
        }

        // ads
        $sAdsQuery  = "SELECT *, UNIX_TIMESTAMP(`DateTime`) AS 'Date_UTS' FROM `ClassifiedsAdvertisements`";
        $rAdsResult = mysql_query($sAdsQuery, $this -> rOldDb);
        while($aAdsRow = mysql_fetch_assoc($rAdsResult)) {
            $iAdID = (int)$aAdsRow['ID'];
            $iAdOwnerID = (int)$aAdsRow['IDProfile'];
            $iAdCatID = (int)$aAdsRow['IDClassifiedsSubs'];
            $sAdDateTime = (int)$aAdsRow['Date_UTS'];
            $sAdSubject = $this -> oMigrationModule -> _oDb  -> escape($aAdsRow['Subject']);
            $sAdUri = $this -> oMigrationModule -> _oDb  -> escape($aAdsRow['EntryUri']);
            $sAdMessage = $this -> oMigrationModule -> _oDb  -> escape($aAdsRow['Message']);
            $sAdStatus = $this -> oMigrationModule -> _oDb  -> escape($aAdsRow['Status']);
            $iAdCustomVal1 = (int)$aAdsRow['CustomFieldValue1'];
            $iAdCustomVal2 = (int)$aAdsRow['CustomFieldValue2'];
            $iAdLifeTime = (int)$aAdsRow['LifeTime'];
            $sAdMedia = $aAdsRow['Media'];
            $sAdTags = $this -> oMigrationModule -> _oDb  -> escape($aAdsRow['Tags']);

            $sCommentsCntSQL  = "SELECT COUNT(*) FROM `CmtsClassifieds` WHERE `cmt_object_id`='{$iAdID}'";
            $rCommentsCntRes = mysql_query($sCommentsCntSQL, $this -> rOldDb);
            $aCommentsCnt = mysql_fetch_array($rCommentsCntRes);
            $iCommentsCnt = (int)$aCommentsCnt[0];

            $sPostQuery =
            "
                INSERT INTO
                    `bx_ads_main`
                SET
                    `ID` = '{$iAdID}',
                    `IDProfile` = '{$iAdOwnerID}',
                    `IDClassifiedsSubs` = '{$iAdCatID}',
                    `DateTime` = '{$sAdDateTime}',
                    `Subject` = '{$sAdSubject}',
                    `EntryUri` = '{$sAdUri}',
                    `Message` = '{$sAdMessage}',
                    `Status` = '{$sAdStatus}',
                    `CustomFieldValue1` = '{$iAdCustomVal1}',
                    `CustomFieldValue2` = '{$iAdCustomVal2}',
                    `LifeTime` = '{$iAdLifeTime}',
                    `Media` = '{$sAdMedia}',
                    `Tags` = '{$sAdTags}',
                    `Country` = '',
                    `City` = '',
                    `Featured` = 0,
                    `Views` = 0,
                    `Rate` = 0,
                    `RateCount` = 0,
                    `CommentsCount` = {$iCommentsCnt},
                    `AllowView` = '3',
                    `AllowRate` = '3',
                    `AllowComment` = '3'
           ";
           $this -> oMigrationModule -> _oDb -> query($sPostQuery);
           $this -> iTransffered++;
        }

        // config_mediaImages
        $sSrcPath = $this -> oMigrationModule -> _oDb -> getExtraParam('config_mediaImages') . 'classifieds/';
        $sDstPath = BX_DIRECTORY_PATH_ROOT . $this -> oDolModule->sUploadDir;

        // ads media
        $sAdMediaQuery  = "SELECT *, UNIX_TIMESTAMP(`MediaDate`) AS 'Date_UTS' FROM `ClassifiedsAdvertisementsMedia`";
        $rAdMediaResult = mysql_query($sAdMediaQuery, $this -> rOldDb);
        while($aAdMediaRow = mysql_fetch_assoc($rAdMediaResult)) {
            $iAdMediaID = (int)$aAdMediaRow['MediaID'];
            $iAdMediaOwnerID = (int)$aAdMediaRow['MediaProfileID'];
            $sAdMediaFile = $this -> oMigrationModule -> _oDb  -> escape($aAdMediaRow['MediaFile']);
            $sAdMediaDate = (int)$aAdMediaRow['Date_UTS'];

            $sAddSubCatQuery =
            "
                INSERT INTO
                    `bx_ads_main_media`
                SET
                    `MediaID` = '{$iAdMediaID}',
                    `MediaProfileID` = '{$iAdMediaOwnerID}',
                    `MediaType` = 'photo',
                    `MediaFile` = '{$sAdMediaFile}',
                    `MediaDate` = '{$sAdMediaDate}'
           ";
           $this -> oMigrationModule -> _oDb -> query($sAddSubCatQuery);

           @copy($sSrcPath . 'img_' . $sAdMediaFile, $sDstPath . '_img_' . $sAdMediaFile);

           $vResizeRes = imageResize( $sDstPath . '_img_' . $sAdMediaFile, $sDstPath . "img_{$sAdMediaFile}", $this -> oDolModule->iImgSize, $this -> oDolModule->iImgSize );
           $vThumbResizeRes = imageResize( $sDstPath . '_img_' . $sAdMediaFile, $sDstPath . "thumb_{$sAdMediaFile}", $this -> oDolModule->iThumbSize, $this -> oDolModule->iThumbSize );
           $vBigThumbResizeRes = imageResize( $sDstPath . '_img_' . $sAdMediaFile, $sDstPath . "big_thumb_{$sAdMediaFile}", $this -> oDolModule->iBigThumbSize, $this -> oDolModule->iBigThumbSize );
           $vIconResizeRes = imageResize( $sDstPath . '_img_' . $sAdMediaFile, $sDstPath . "icon_{$sAdMediaFile}", $this -> oDolModule->iIconSize, $this -> oDolModule->iIconSize );

           @unlink ($sDstPath . '_img_' . $sAdMediaFile);
        }

        // moving comments
        $sCommQuery = "SELECT * FROM `CmtsClassifieds`";
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
                INSERT INTO `bx_ads_cmts` (`cmt_id`, `cmt_parent_id`, `cmt_object_id`, `cmt_author_id`, `cmt_text`, `cmt_mood`, `cmt_rate`, `cmt_rate_count`, `cmt_time`, `cmt_replies`) VALUES ({$iCmtID}, {$iCmtParentID}, {$iCmtObjectID}, {$iCmtAuthorID}, '{$sCmtText}', 0, {$iCmtRate}, {$iCmtRateCnt}, '{$sCmtTime}', {$sCmtReplies});
            ";
            $this -> oMigrationModule -> _oDb -> query($sCommSQL);
        }
    }
}

?>