<?
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

bx_import('BxDolTwigModuleDb');

/*
 * Events module Data
 */
class BxEventsDb extends BxDolTwigModuleDb {

    /*
     * Constructor.
     */
    function BxEventsDb(&$oConfig) {
        parent::BxDolTwigModuleDb($oConfig);

        $this->_sTableMain = 'main';
        $this->_sTableMediaPrefix = '';
        $this->_sFieldId = 'ID';
        $this->_sFieldAuthorId = 'ResponsibleID';
        $this->_sFieldUri = 'EntryUri';
        $this->_sFieldTitle = 'Title';
        $this->_sFieldDescription = 'Description';
        $this->_sFieldTags = 'Tags';
        $this->_sFieldThumb = 'PrimPhoto';
        $this->_sFieldStatus = 'Status';
        $this->_sFieldFeatured = 'Featured';
        $this->_sFieldCreated = 'Date';
        $this->_sFieldJoinConfirmation = 'JoinConfirmation';
        $this->_sFieldFansCount = 'FansCount';
        $this->_sTableFans = 'participants';
        $this->_sTableAdmins = 'admins';
        $this->_sFieldAllowViewTo = 'allow_view_event_to';
    }

    function getUpcomingEvent ($isFeatured) {
        $sWhere = '';
        if ($isFeatured)
            $sWhere = " AND `{$this->_sFieldFeatured}` = '1' ";
        return $this->getRow ("SELECT * FROM `" . $this->_sPrefix . "main` WHERE `EventStart` > UNIX_TIMESTAMP() AND `Status` = 'approved' AND `{$this->_sFieldAllowViewTo}` = '" . BX_DOL_PG_ALL . "' $sWhere ORDER BY `EventStart` ASC LIMIT 1");
    }

    function getEntriesByMonth ($iYear, $iMonth, $iNextYear, $iNextMonth) {
        return $this->getAll ("SELECT *, DAYOFMONTH(FROM_UNIXTIME(`EventStart`)) AS `Day`
            FROM `" . $this->_sPrefix . "main`
            WHERE `EventStart` >= UNIX_TIMESTAMP('$iYear-$iMonth-1') AND `EventStart` < UNIX_TIMESTAMP('$iNextYear-$iNextMonth-1') AND `Status` = 'approved'");
    }

    function deleteEntryByIdAndOwner ($iId, $iOwner, $isAdmin) {
        if ($iRet = parent::deleteEntryByIdAndOwner ($iId, $iOwner, $isAdmin)) {
            $this->query ("DELETE FROM `" . $this->_sPrefix . "participants` WHERE `id_entry` = $iId");
            $this->deleteEntryMediaAll ($iId, 'images');
            $this->deleteEntryMediaAll ($iId, 'videos');
            $this->deleteEntryMediaAll ($iId, 'sounds');
            $this->deleteEntryMediaAll ($iId, 'files');
        }
        return $iRet;
    }

}

?>
