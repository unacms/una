<?

// TODO: decide later what to do with twig* classes and module, it looks like they will stay and 'complex' modules will be still based on it, but some refactoring is needed

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

bx_import('BxDolModuleDb');

/**
 * Base database class for modules like events/groups/store
 */
class BxDolTwigModuleDb extends BxDolModuleDb {

    var $_sTableMain = 'main';
    var $_sTableMediaPrefix = '';
    var $_sFieldId = 'id';
    var $_sFieldAuthorId = 'author_id';
    var $_sFieldUri = 'uri';
    var $_sFieldTitle = 'title';
    var $_sFieldDescription = 'desc';
    var $_sFieldTags = 'tags';
    var $_sFieldThumb = 'thumb';
    var $_sFieldStatus = 'status';
    var $_sFieldFeatured = 'featured';
    var $_sFieldCreated = 'created';
    var $_sFieldDesc = 'desc';
    var $_sFieldFansCount = 'fans_count';
    var $_sTableFans = 'fans';
    var $_sTableAdmins = 'admins';
    var $_sFieldAllowViewTo = 'allow_view_to';
    var $_sFieldCommentCount = 'comments_count';

    /**
     * Constructor.
     */
    function BxDolTwigModuleDb(&$oConfig) {
        parent::__construct($oConfig);
    }

    // entry functions

    function isAnyPublicContent() {
        return $this->getOne ("SELECT `{$this->_sFieldId}` FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldStatus}` = 'approved' AND `{$this->_sFieldAllowViewTo}` = '" . BX_DOL_PG_ALL . "' LIMIT 1");
    }

    function getEntryByIdAndOwner ($iId, $iOwner, $isAdmin) {
        $sWhere = '';
        if (!$isAdmin)
            $sWhere = " AND `{$this->_sFieldAuthorId}` = '$iOwner' ";
        return $this->getRow ("SELECT * FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldId}` = $iId $sWhere LIMIT 1");
    }

    function getEntryById ($iId) {
        return $this->getEntryByIdAndOwner ($iId, 0, true);
    }

    function getEntriesByAuthor($iProfileId) {
        return $this->getPairs ("SELECT `{$this->_sFieldId}` FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldAuthorId}` = '$iProfileId'", $this->_sFieldId, $this->_sFieldId);
    }

    function getCountByAuthorAndStatus($iProfileId, $sStatus) {
        return $this->getOne ("SELECT COUNT(*) FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldStatus}` = '$sStatus' AND `{$this->_sFieldAuthorId}` = '$iProfileId'");
    }

    function getEntryByUri ($sUri) {
        return $this->getRow ("SELECT * FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldUri}` = '$sUri' LIMIT 1");
    }

    function getLatestFeaturedItem () {
        $sWhere = " AND `{$this->_sFieldFeatured}` = '1' ";
        return $this->getRow ("SELECT * FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldStatus}` = 'approved' AND `{$this->_sFieldAllowViewTo}` = '" . BX_DOL_PG_ALL . "' $sWhere ORDER BY `{$this->_sFieldCreated}` DESC LIMIT 1");
    }

    function getEntriesByMonth ($iYear, $iMonth, $iNextYear, $iNextMonth) {
        return $this->getAll ("SELECT *, DAYOFMONTH(FROM_UNIXTIME(`{$this->_sFieldCreated}`)) AS `Day`
            FROM `" . $this->_sPrefix . $this->_sTableMain . "`
            WHERE `{$this->_sFieldCreated}` >= UNIX_TIMESTAMP('$iYear-$iMonth-1') AND `{$this->_sFieldCreated}` < UNIX_TIMESTAMP('$iNextYear-$iNextMonth-1') AND `{$this->_sFieldStatus}` = 'approved'");
    }

    function deleteEntryByIdAndOwner ($iId, $iOwner, $isAdmin) {
        $sWhere = '';
        if (!$isAdmin)
            $sWhere = " AND `{$this->_sFieldAuthorId}` = '$iOwner' ";
        if (!($iRet = $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldId}` = $iId $sWhere LIMIT 1")))
            return false;
        $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "images` WHERE `entry_id` = $iId");
        return true;
    }

    function markAsFeatured ($iId) {
        return $this->query ("UPDATE `" . $this->_sPrefix . $this->_sTableMain . "` SET `{$this->_sFieldFeatured}` = (`{$this->_sFieldFeatured}` - 1)*(`{$this->_sFieldFeatured}` - 1) WHERE `{$this->_sFieldId}` = $iId LIMIT 1");
    }

    function activateEntry ($iId) {
        return $this->query ("UPDATE `" . $this->_sPrefix . $this->_sTableMain . "` SET `{$this->_sFieldStatus}` = 'approved' WHERE `{$this->_sFieldId}` = $iId LIMIT 1");
    }

    // media functions

    function updateMedia ($iEntryId, $aMediaAdd, $aMediaDelete, $sMediaType) {
        $this->deleteMedia ($iEntryId, $aMediaDelete, $sMediaType);
        return $this->insertMedia ($iEntryId, $aMediaAdd, $sMediaType);
    }

    function insertMedia ($iEntryId, $aMedia, $sMediaType) {
        if (!$aMedia)
            return false;
        if (is_array($aMedia))
            $sValues = implode ("), ($iEntryId, ", $aMedia);
        else
            $sValues = (int)$aMedia;
        return $this->query ("INSERT IGNORE INTO `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` VALUES ($iEntryId, $sValues)");
    }

    function deleteMedia ($iEntryId, $aMedia, $sMediaType) {
        if (!$aMedia)
            return false;
        if (is_array($aMedia))
            $sValues = implode ("') OR (`entry_id` = $iEntryId AND `media_id` = '", $aMedia);
        else
            $sValues = (int)$aMedia;
        return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE (`entry_id` = '$iEntryId' AND `media_id` = '$sValues')");
    }

    function deleteEntryMediaAll ($iEntryId, $sMediaType) {
        return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE `entry_id` = '$iEntryId'");
    }

    function deleteMediaFile ($iMediaId, $sMediaType) {
        return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE `media_id` = '$iMediaId'");
    }

    function getMediaIds ($iEntryId, $sMediaType) {
        return $this->getPairs ("SELECT `media_id` FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE `entry_id` = '$iEntryId'", 'media_id', 'media_id');
    }

    function isMediaInUse ($iMediaId, $sMediaType) {
        return $this->getOne ("SELECT `entry_id` FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE `media_id` = '$iEntryId' LIMIT 1");
    }

    function getMedia ($iEntryId, $iMediaId, $sMediaType) {
        return $this->getRow ("SELECT `entry_id`, `media_id` FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix . "{$sMediaType}` WHERE `entry_id` = '$iEntryId' AND `media_id` = '$iMediaId'");
    }

    function setThumbnail ($iEntryId, $iImageId) {
        if (!$iImageId) {
            $iOldThumbId = $this->getOne ("SELECT `{$this->_sFieldThumb}` FROM `" . $this->_sPrefix . $this->_sTableMain . "` WHERE `{$this->_sFieldId}` = '$iEntryId' LIMIT 1");
            if ($iOldThumbId > 0 && $this->getOne("SELECT `entry_id` FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix. "images` WHERE `media_id` = '$iOldThumbId' LIMIT 1") > 0)
                return false;
            $iImageId = $this->getOne("SELECT `media_id` FROM `" . $this->_sPrefix . $this->_sTableMediaPrefix. "images` WHERE `entry_id` = '$iEntryId' LIMIT 1");
        }

        if (!$iImageId)
            return false;
        return $this->query ("UPDATE `" . $this->_sPrefix . $this->_sTableMain . "` SET `{$this->_sFieldThumb}` = '$iImageId' WHERE `{$this->_sFieldId}` = '$iEntryId' LIMIT 1");
    }

    // profile functions

    function getProfileNickNameById ($iId) {
        return 'Undefined'; // TODO:
    }

    function getProfileIdByNickName ($sNick, $isProcessDbInput = true) {
        if ($isProcessDbInput)
            $sNick = process_db_input ($sNick, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION);
        return $this->getOne ("SELECT `ID` FROM `Profiles` WHERE `NickName` = '$sNick' LIMIT 1");
    }

    // settings functions

    function getSettingsCategory($sName) {
        return $this->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = '{$sName}' LIMIT 1");
    }

    function getPotentialVisitors ($iProfileId) {
        $a = $this->getAllWithKey ("SELECT `p`.`NickName`, `p`.`ID` FROM `sys_friend_list` AS `o` INNER JOIN `Profiles` AS `p` ON (`p`.`ID` = `o`.`ID` AND `o`.`Profile` = '$iProfileId')", 'NickName');
        $a = array_merge($a, $this->getAllWithKey ("SELECT `p`.`NickName`, `p`.`ID` FROM `sys_friend_list` AS `o` INNER JOIN `Profiles` AS `p` ON (`p`.`ID` = `o`.`Profile` AND `o`.`ID` = '$iProfileId')", 'NickName'));
        $a = array_merge($a, $this->getAllWithKey ("SELECT `p`.`NickName`, `p`.`ID` FROM `sys_fave_list` AS `o` INNER JOIN `Profiles` AS `p` ON (`p`.`ID` = `o`.`ID` AND `o`.`Profile` = '$iProfileId')", 'NickName'));
        $a = array_merge($a, $this->getAllWithKey ("SELECT `p`.`NickName`, `p`.`ID` FROM `sys_fave_list` AS `o` INNER JOIN `Profiles` AS `p` ON (`p`.`ID` = `o`.`Profile` AND `o`.`ID` = '$iProfileId')", 'NickName'));
        $a = array_merge($a, $this->getAllWithKey ("SELECT `p`.`NickName`, `p`.`ID` FROM `sys_messages` AS `o` INNER JOIN `Profiles` AS `p` ON (`p`.`ID` = `o`.`Recipient` AND `o`.`Sender` = '$iProfileId')", 'NickName'));
        foreach ($a as $k => $r) {
            if ($iProfileId == $r['ID']) {
                unset($a[$k]);
                break;
            }
        }
        asort ($a);
        return $a;
    }


    // fans and admins functions

    function getBroadcastRecipients ($iEntryId) {
        return $this->getAll ("SELECT DISTINCT `p`.`ID`, `p`.`Email` FROM `" . $this->_sPrefix . $this->_sTableFans . "` AS `f` INNER JOIN `Profiles` as `p` ON (`f`.`id_entry` = '$iEntryId' AND `f`.`id_profile` = `p`.`ID` AND `f`.`confirmed` = 1 AND `p`.`Status` = 'Active')");
    }

    function joinEntry($iEntryId, $iProfileId, $isConfirmed) {
        $isConfirmed = $isConfirmed ? 1 : 0;
        $iRet = $this->query ("INSERT IGNORE INTO `" . $this->_sPrefix . $this->_sTableFans . "` SET `id_entry` = '$iEntryId', `id_profile` = '$iProfileId', `confirmed` = '$isConfirmed', `when` = '" . time() . "'");
        if ($iRet && $isConfirmed)
            $this->query ("UPDATE `" . $this->_sPrefix . "main` SET `" . $this->_sFieldFansCount . "` = `" . $this->_sFieldFansCount . "` + 1 WHERE `id` = '$iEntryId'");
        return $iRet;
    }

    function leaveEntry ($iEntryId, $iProfileId) {
        $isConfirmed = $this->getOne ("SELECT `confirmed` FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = '$iProfileId' LIMIT 1");
        $iRet = $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = '$iProfileId'");
        if ($iRet && $isConfirmed)
            $this->query ("UPDATE `" . $this->_sPrefix . "main` SET `" . $this->_sFieldFansCount . "` = `" . $this->_sFieldFansCount . "` - 1 WHERE `id` = '$iEntryId'");
        return $iRet;
    }

    function isFan($iEntryId, $iProfileId, $isConfirmed) {
        $isConfirmed = $isConfirmed ? 1 : 0;
        return $this->getOne ("SELECT `when` FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = '$iProfileId' AND `confirmed` = '$isConfirmed' LIMIT 1");
    }

    function getFansBrowse(&$aProfiles, $iEntryId, $iStart, $iMaxNum) {
        return $this->getFans($aProfiles, $iEntryId, true, $iStart, $iMaxNum);
    }

    function getFans(&$aProfiles, $iEntryId, $isConfirmed, $iStart, $iMaxNum, $aFilter = array()) {
        $isConfirmed = $isConfirmed ? 1 : 0;
        $sFilter = '';
        if ($aFilter) {
            $s = implode (' OR `f`.`id_profile` = ', $aFilter);
            $sFilter = ' AND (`f`.`id_profile` = ' . $s . ') ';
        }
        $aProfiles = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS `p`.* FROM `Profiles` AS `p` INNER JOIN `" . $this->_sPrefix . $this->_sTableFans . "` AS `f` ON (`f`.`id_entry` = '$iEntryId' AND `f`.`id_profile` = `p`.`ID` AND `f`.`confirmed` = $isConfirmed AND `p`.`Status` = 'Active' $sFilter) ORDER BY `f`.`when` DESC LIMIT $iStart, $iMaxNum");
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function confirmFans ($iEntryId, $aProfileIds) {
        if (!$aProfileIds)
            return false;
        $s = implode (' OR `id_profile` = ', $aProfileIds);
        $iRet = $this->query ("UPDATE `" . $this->_sPrefix . $this->_sTableFans . "` SET `confirmed` = 1 WHERE `id_entry` = '$iEntryId' AND `confirmed` = 0 AND (`id_profile` = $s)");
        if ($iRet)
            $this->query ("UPDATE `" . $this->_sPrefix . "main` SET `" . $this->_sFieldFansCount . "` = `" . $this->_sFieldFansCount . "` + $iRet WHERE `id` = '$iEntryId'");
        return $iRet;
    }

    function removeFans ($iEntryId, $aProfileIds) {
        if (!$aProfileIds)
            return false;
        $s = implode (' OR `id_profile` = ', $aProfileIds);
        $iRet = $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_entry` = '$iEntryId' AND `confirmed` = 1 AND (`id_profile` = $s)");
        if ($iRet)
            $this->query ("UPDATE `" . $this->_sPrefix . "main` SET `" . $this->_sFieldFansCount . "` = `" . $this->_sFieldFansCount . "` - $iRet WHERE `id` = '$iEntryId'");
        if ($iRet && $this->_sTableAdmins)
            $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableAdmins . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = $s");
        return $iRet;
    }

    function removeFanFromAllEntries ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        if (!$iProfileId || !$this->_sTableFans)
            return false;

        // delete unconfirmed fans
        $iDeleted = $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `confirmed` = 0 AND `id_profile` = " . $iProfileId);

        // delete confirmed fans
        $aEntries = $this->getColumn("SELECT DISTINCT `id_entry` FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_profile` = " . $iProfileId);
        foreach ($aEntries as $iEntryId) {
            $iDeleted += $this->leaveEntry ($iEntryId, $iProfileId) ? 1 : 0;
        }        

        return $iDeleted;
    }

    function removeAdminFromAllEntries ($iProfileId) {
        $iProfileId = (int)$iProfileId;
        if (!$iProfileId || !$this->_sTableAdmins)
            return false;

        return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableAdmins . "` WHERE `id_profile` = " . $iProfileId);
    }


    function rejectFans ($iEntryId, $aProfileIds) {
        if (!$aProfileIds)
            return false;
        $s = implode (' OR `id_profile` = ', $aProfileIds);
        return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableFans . "` WHERE `id_entry` = '$iEntryId' AND `confirmed` = 0 AND (`id_profile` = $s)");
    }

    function getAdmins(&$aProfiles, $iEntryId, $iStart, $iMaxNum, $aFilter = array()) {
        $sFilter = '';
        if ($aFilter) {
            $s = implode (' OR `f`.`id_profile` = ', $aFilter);
            $sFilter = ' AND (`f`.`id_profile` = ' . $s . ') ';
        }
        $aProfiles = $this->getAll ("SELECT SQL_CALC_FOUND_ROWS `p`.* FROM `Profiles` AS `p` INNER JOIN `" . $this->_sPrefix . $this->_sTableAdmins . "` AS `f` ON (`f`.`id_entry` = '$iEntryId' AND `f`.`id_profile` = `p`.`ID` AND `p`.`Status` = 'Active' $sFilter) ORDER BY `f`.`when` DESC LIMIT $iStart, $iMaxNum");
        return $this->getOne("SELECT FOUND_ROWS()");
    }

    function isGroupAdmin($iEntryId, $iProfileId) {
        return $this->getOne ("SELECT `when` FROM `" . $this->_sPrefix . $this->_sTableAdmins . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = '$iProfileId' LIMIT 1");
    }

    function addGroupAdmin($iEntryId, $aProfileIds) {
        if (is_array($aProfileIds)) {
            $iRet = 0;
            foreach ($aProfileIds AS $iProfileId)
                $iRet += $this->query ("INSERT IGNORE INTO `" . $this->_sPrefix . $this->_sTableAdmins . "` SET `id_entry` = '$iEntryId', `id_profile` = '$iProfileId', `when` = '" . time() . "'");
            return $iRet;
        } else {
            return $this->query ("INSERT IGNORE INTO `" . $this->_sPrefix . $this->_sTableAdmins . "` SET `id_entry` = '$iEntryId', `id_profile` = '$aProfileIds', `when` = '" . time() . "'");
        }
    }

    function removeGroupAdmin($iEntryId, $aProfileIds) {
        if (!$aProfileIds)
            return false;
        if (is_array($aProfileIds)) {
            $s = implode (' OR `id_profile` = ', $aProfileIds);
            return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableAdmins . "` WHERE `id_entry` = '$iEntryId' AND (`id_profile` = $s)");
        } else {
            return $this->query ("DELETE FROM `" . $this->_sPrefix . $this->_sTableAdmins . "` WHERE `id_entry` = '$iEntryId' AND `id_profile` = '$aProfileIds'");
        }
    }

}

?>
