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

bx_import ('BxTemplPage');

/**
 * Base entry view class for modules like events/groups/store
 */
class BxDolTwigPageView extends BxTemplPage {

    function __construct($aObject, $oTemplate) {
        parent::__construct($aObject, $oTemplate);
    }

    function initWithUri ($sPageUri) {
        $this->addMarkers(array(
            'uri' => $sPageUri,
        ));
    }

    protected function _getPageCacheParams () {
        return ''; // TODO: return URI 
    }

    function _blockFans($iPerPage, $sFuncIsAllowed = 'isAllowedViewFans', $sFuncGetFans = 'getFans') {

        return 'TODO: fans here';
/*
        if (!$this->_oMain->$sFuncIsAllowed($this->aDataEntry))
            return '';

        $iPage = (int)$_GET['page'];
        if( $iPage < 1)
            $iPage = 1;
        $iStart = ($iPage - 1) * $iPerPage;

        $aProfiles = array ();
        $iNum = $this->_oDb->$sFuncGetFans($aProfiles, $this->aDataEntry[$this->_oDb->_sFieldId], true, $iStart, $iPerPage);
        if (!$iNum || !$aProfiles)
            return MsgBox(_t("_Empty"));
        $iPages = ceil($iNum / $iPerPage);

        bx_import('BxTemplSearchProfile');
        $oBxTemplSearchProfile = new BxTemplSearchProfile();
        $sMainContent = '';
        foreach ($aProfiles as $aProfile) {
            $sMainContent .= $oBxTemplSearchProfile->displaySearchUnit($aProfile);
        }
        $ret .= $GLOBALS['oFunctions']->centerContent($sMainContent, '.searchrow_block_simple');

        $aDBBottomMenu = array();
        if ($iPages > 1) {
            $sUrlStart = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "view/".$this->aDataEntry[$this->_oDb->_sFieldUri];
            $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');
            if ($iPage > 1)
                $aDBBottomMenu[_t('_Back')] = array('href' => $sUrlStart . "page=" . ($iPage - 1), 'dynamic' => true, 'class' => 'backMembers', 'icon' => getTemplateIcon('sys_back.png'), 'icon_class' => 'left', 'static' => false);
            if ($iPage < $iPages) {
                $aDBBottomMenu[_t('_Next')] = array('href' => $sUrlStart . "page=" . ($iPage + 1), 'dynamic' => true, 'class' => 'moreMembers', 'icon' => getTemplateIcon('sys_next.png'), 'static' => false);
            }
        }
        //$aDBBottomMenu[_t('_View All')] = array('href' => BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "fans/".$this->aDataEntry['uri'], 'class' => 'view_all', 'static' => true);

        $ret .= '<div class="clear_both"></div>';

        return array($ret, array(), $aDBBottomMenu);
*/
    }

    function _blockFansUnconfirmed($iFansLimit = 1000) {

        return 'TODO: unconfirmed fans here';
/*
        if (!$this->_oMain->isEntryAdmin($this->aDataEntry))
            return '';

        $aProfiles = array ();
        $iNum = $this->_oDb->getFans($aProfiles, $this->aDataEntry[$this->_oDb->_sFieldId], false, 0, $iFansLimit);
        if (!$iNum)
            return MsgBox(_t('_Empty'));

        $sActionsUrl = BX_DOL_URL_ROOT . $this->_oMain->_oConfig->getBaseUri() . "view/" . $this->aDataEntry[$this->_oDb->_sFieldUri] . '?ajax_action=';
        $aButtons = array (
            array (
                'type' => 'submit',
                'name' => 'fans_reject',
                'value' => _t('_sys_btn_fans_reject'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_unconfirmed_fans_content', '{$sActionsUrl}reject&ids=' + sys_manage_items_get_unconfirmed_fans_ids(), false, 'post'); return false;\"",
            ),
            array (
                'type' => 'submit',
                'name' => 'fans_confirm',
                'value' => _t('_sys_btn_fans_confirm'),
                'onclick' => "onclick=\"getHtmlData('sys_manage_items_unconfirmed_fans_content', '{$sActionsUrl}confirm&ids=' + sys_manage_items_get_unconfirmed_fans_ids(), false, 'post'); return false;\"",
            ),
        );
        bx_import ('BxTemplSearchResult');
        $sControl = BxTemplSearchResult::showAdminActionsPanel('sys_manage_items_unconfirmed_fans', $aButtons, 'sys_fan_unit');
        $aVars = array(
            'suffix' => 'unconfirmed_fans',
            'content' => $this->_oMain->_profilesEdit($aProfiles),
            'control' => $sControl,
        );
        return $this->_oMain->_oTemplate->parseHtmlByName('manage_items_form', $aVars);
*/
    }
}
