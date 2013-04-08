<?php
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

bx_import('BxDolPageView');

class BxEventsPageMy extends BxDolPageView {

    var $_oMain;
    var $_oTemplate;
    var $_oConfig;
    var $_oDb;
    var $_aProfile;

    function BxEventsPageMy(&$oMain, &$aProfile) {
        $this->_oMain = &$oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oConfig = $oMain->_oConfig;
        $this->_oDb = $oMain->_oDb;
        $this->_aProfile = &$aProfile;
        parent::BxDolPageView('bx_events_my');
    }

    function getBlockCode_Owner() {
        if (!$this->_oMain->_iProfileId || !$this->_aProfile)
            return '';

        $sContent = '';
        switch (bx_get('bx_events_filter')) {
        case 'add_event':
            $sContent = $this->getBlockCode_Add ();
            break;
        case 'manage_events':
            $sContent = $this->getBlockCode_Manage ();
            break;
        case 'pending_events':
            $sContent = $this->getBlockCode_Pending ();
            break;
        default:
            $sContent = $this->getBlockCode_Main ();
        }

        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "browse/my";
        $aMenu = array(
            _t('_bx_events_block_submenu_main') => array('href' => $sBaseUrl, 'active' => !bx_get('bx_events_filter')),
            _t('_bx_events_block_submenu_add') => array('href' => $sBaseUrl . '&bx_events_filter=add_event', 'active' => 'add_event' == bx_get('bx_events_filter')),
            _t('_bx_events_block_submenu_manage') => array('href' => $sBaseUrl . '&bx_events_filter=manage_events', 'active' => 'manage_events' == bx_get('bx_events_filter')),
            _t('_bx_events_block_submenu_pending') => array('href' => $sBaseUrl . '&bx_events_filter=pending_events', 'active' => 'pending_events' == bx_get('bx_events_filter')),
        );
        return array($sContent, $aMenu, '', '');
    }

    function getBlockCode_Browse() {

        bx_events_import ('SearchResult');
        $o = new BxEventsSearchResult('user', process_db_input ($this->_aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION));
        $o->aCurrent['rss'] = 0;

        $o->sBrowseUrl = "browse/my";
        $o->aCurrent['title'] = _t('_bx_events_block_my_events');

        if ($o->isError) {
            return MsgBox(_t('_Empty'));
        }

        if ($s = $o->processing()) {
            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            return $s;
        } else {
            return DesignBoxContent(_t('_bx_events_block_user_events'), MsgBox(_t('_Empty')), 1);
        }
    }

    function getBlockCode_Main() {
        $iActive = $this->_oDb->getCountByAuthorAndStatus($this->_aProfile['ID'], 'approved');
        $iPending = $this->_oDb->getCountByAuthorAndStatus($this->_aProfile['ID'], 'pending');
        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "browse/my";
        $aVars = array ('msg' => '');
        if ($iPending)
            $aVars['msg'] = sprintf(_t('_bx_events_msg_you_have_pending_approval_events'), $sBaseUrl . '&bx_events_filter=pending_events', $iPending);
        elseif (!$iActive)
            $aVars['msg'] = sprintf(_t('_bx_events_msg_you_have_no_events'), $sBaseUrl . '&bx_events_filter=add_event');
        else
            $aVars['msg'] = sprintf(_t('_bx_events_msg_you_have_some_events'), $sBaseUrl . '&bx_events_filter=manage_events', $iActive, $sBaseUrl . '&bx_events_filter=add_event');
        return $this->_oTemplate->parseHtmlByName('my_events_main', $aVars);
    }

    function getBlockCode_Add() {
        if (!$this->_oMain->isAllowedAdd()) {
            return MsgBox(_t('_Access denied'));
        }
        ob_start();
        $this->_oMain->_addForm(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/my');
        $aVars = array ('form' => ob_get_clean());
        $this->_oTemplate->addCss ('forms_extra.css');
        return $this->_oTemplate->parseHtmlByName('my_events_create_event', $aVars);
    }

    function getBlockCode_Manage() {
        $sForm = $this->_oMain->_manageEntries ('user', process_db_input ($this->_aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), false, 'bx_events_my_active', array(
                'action_delete' => '_bx_events_admin_delete',
        ), 'bx_events_my_active', 7);
        $aVars = array ('form' => $sForm, 'id' => 'bx_events_my_active');
        return $this->_oTemplate->parseHtmlByName('my_events_manage', $aVars);
    }

    function getBlockCode_Pending() {
        $sForm = $this->_oMain->_manageEntries ('my_pending', '', false, 'bx_events_my_pending', array(
                'action_delete' => '_bx_events_admin_delete',
        ), 'bx_events_my_pending', 7);
        $aVars = array ('form' => $sForm, 'id' => 'bx_events_my_pending');
        return $this->_oTemplate->parseHtmlByName('my_events_manage', $aVars);
    }
}

?>
