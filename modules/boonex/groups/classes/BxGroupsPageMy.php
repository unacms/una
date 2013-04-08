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

class BxGroupsPageMy extends BxDolPageView {

    var $_oMain;
    var $_oTemplate;
    var $_oDb;
    var $_oConfig;
    var $_aProfile;

    function BxGroupsPageMy(&$oMain, &$aProfile) {
        $this->_oMain = &$oMain;
        $this->_oTemplate = $oMain->_oTemplate;
        $this->_oDb = $oMain->_oDb;
        $this->_oConfig = $oMain->_oConfig;
        $this->_aProfile = $aProfile;
        parent::BxDolPageView('bx_groups_my');
    }

    function getBlockCode_Owner() {
        if (!$this->_oMain->_iProfileId || !$this->_aProfile)
            return '';

        $sContent = '';
        switch (bx_get('bx_groups_filter')) {
        case 'add_group':
            $sContent = $this->getBlockCode_Add ();
            break;
        case 'manage_groups':
            $sContent = $this->getBlockCode_My ();
            break;
        case 'pending_groups':
            $sContent = $this->getBlockCode_Pending ();
            break;
        default:
            $sContent = $this->getBlockCode_Main ();
        }

        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "browse/my";
        $aMenu = array(
            _t('_bx_groups_block_submenu_main') => array('href' => $sBaseUrl, 'active' => !bx_get('bx_groups_filter')),
            _t('_bx_groups_block_submenu_add_group') => array('href' => $sBaseUrl . '&bx_groups_filter=add_group', 'active' => 'add_group' == bx_get('bx_groups_filter')),
            _t('_bx_groups_block_submenu_manage_groups') => array('href' => $sBaseUrl . '&bx_groups_filter=manage_groups', 'active' => 'manage_groups' == bx_get('bx_groups_filter')),
            _t('_bx_groups_block_submenu_pending_groups') => array('href' => $sBaseUrl . '&bx_groups_filter=pending_groups', 'active' => 'pending_groups' == bx_get('bx_groups_filter')),
        );
        return array($sContent, $aMenu, '', '');
    }

    function getBlockCode_Browse() {

        bx_groups_import ('SearchResult');
        $o = new BxGroupsSearchResult('user', process_db_input ($this->_aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION));
        $o->aCurrent['rss'] = 0;

        $o->sBrowseUrl = "browse/my";
        $o->aCurrent['title'] = _t('_bx_groups_page_title_my_groups');

        if ($o->isError) {
            return DesignBoxContent(_t('_bx_groups_block_users_groups'), MsgBox(_t('_Empty')), 1);
        }

        if ($s = $o->processing()) {
            $this->_oTemplate->addCss ('unit.css');
            $this->_oTemplate->addCss ('main.css');
            return $s;
        } else {
            return DesignBoxContent(_t('_bx_groups_block_users_groups'), MsgBox(_t('_Empty')), 1);
        }
    }

    function getBlockCode_Main() {
        $iActive = $this->_oDb->getCountByAuthorAndStatus($this->_aProfile['ID'], 'approved');
        $iPending = $this->_oDb->getCountByAuthorAndStatus($this->_aProfile['ID'], 'pending');
        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "browse/my";
        $aVars = array ('msg' => '');
        if ($iPending)
            $aVars['msg'] = sprintf(_t('_bx_groups_msg_you_have_pending_approval_groups'), $sBaseUrl . '&bx_groups_filter=pending_groups', $iPending);
        elseif (!$iActive)
            $aVars['msg'] = sprintf(_t('_bx_groups_msg_you_have_no_groups'), $sBaseUrl . '&bx_groups_filter=add_group');
        else
            $aVars['msg'] = sprintf(_t('_bx_groups_msg_you_have_some_groups'), $sBaseUrl . '&bx_groups_filter=manage_groups', $iActive, $sBaseUrl . '&bx_groups_filter=add_group');
        return $this->_oTemplate->parseHtmlByName('my_groups_main', $aVars);
    }

    function getBlockCode_Add() {
        if (!$this->_oMain->isAllowedAdd()) {
            return MsgBox(_t('_Access denied'));
        }
        ob_start();
        $this->_oMain->_addForm(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/my');
        $aVars = array ('form' => ob_get_clean(), 'id' => '');
        $this->_oTemplate->addCss ('forms_extra.css');
        return $this->_oTemplate->parseHtmlByName('my_groups_create_group', $aVars);
    }

    function getBlockCode_Pending() {
        $sForm = $this->_oMain->_manageEntries ('my_pending', '', false, 'bx_groups_pending_user_form', array(
            'action_delete' => '_bx_groups_admin_delete',
        ), 'bx_groups_my_pending', false, 7);
        if (!$sForm)
            return MsgBox(_t('_Empty'));
        $aVars = array ('form' => $sForm, 'id' => 'bx_groups_my_pending');
        return $this->_oTemplate->parseHtmlByName('my_groups_manage', $aVars);
    }

    function getBlockCode_My() {
        $sForm = $this->_oMain->_manageEntries ('user', process_db_input ($this->_aProfile['NickName'], BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION), false, 'bx_groups_user_form', array(
            'action_delete' => '_bx_groups_admin_delete',
        ), 'bx_groups_my_active', true, 7);
        $aVars = array ('form' => $sForm, 'id' => 'bx_groups_my_active');
        return $this->_oTemplate->parseHtmlByName('my_groups_manage', $aVars);
    }
}

?>
