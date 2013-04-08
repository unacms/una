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

bx_import('BxDolTextData');

class BxNewsData extends BxDolTextData {
    function BxNewsData(&$oModule) {
        parent::BxDolTextData('news', $oModule->_oConfig->getUri());

        $this->_oModule = &$oModule;

        $this->_aForm['params']['db']['table'] = $this->_oModule->_oDb->getPrefix() . 'entries';
        $this->_aForm['form_attrs']['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'admin/';
        $this->_aForm['inputs']['author_id']['value'] = 0;
        $this->_aForm['inputs']['snippet']['checker']['params'][1] = $this->_oModule->_oConfig->getSnippetLength();
        $this->_aForm['inputs']['allow_comment_to'] = array(
            'type' => 'hidden',
            'name' => 'comment',
            'value' => 0,
            'db' => array (
                'pass' => 'Int',
            ),
        );
        $this->_aForm['inputs']['allow_vote_to'] = array(
            'type' => 'hidden',
            'name' => 'vote',
            'value' => 0,
            'db' => array (
                'pass' => 'Int',
            ),
        );
    }
}
?>