<?php

// TODO: decide later what to do with text* classes and module, it looks like they will stay and text modules will be still based on it, but some refactoring is needed


/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolPageView');
bx_import('BxTemplConfig');

class BxDolTextPageView extends BxDolPageView {
    var $_sPageName;
    var $_sName;
    var $_oObject;

    function BxDolTextPageView($sPageName, $sName, &$oObject) {
        parent::BxDolPageView($sPageName);

        $this->_sName = process_db_input($sName, BX_TAGS_STRIP);
        $this->_oObject = $oObject;
    }
    function getBlockCode_Content() {
        return $this->_oObject->getBlockView($this->_sName);
    }
    function getBlockCode_Comment() {
        return $this->_oObject->getBlockComment($this->_sName);
    }
    function getBlockCode_Vote() {
        $sContent = $this->_oObject->getBlockVote($this->_sName);
        return !empty($sContent) ? array($sContent, array(), array(), false) : '';
    }
    function getBlockCode_Action() {
        return $this->_oObject->getBlockAction($this->_sName);
    }
    function getBlockCode_SocialSharing() {
        return $this->_oObject->getBlockSocialSharing($this->_sName);
    }
}
?>
