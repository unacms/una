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

class BxDolTextPageMain extends BxDolPageView {
    var $_sPageName;
    var $_oObject;

    function BxDolTextPageMain($sPageName, &$oObject) {
        parent::BxDolPageView($sPageName);

        $this->_oObject = $oObject;
    }
    function getBlockCode_Featured() {
        return $this->_oObject->serviceFeaturedBlock();
    }
    function getBlockCode_Latest() {
        bx_import('BxDolTemplate');

        $sUri = $this->_oObject->_oConfig->getUri();
        $sBaseUri = $this->_oObject->_oConfig->getBaseUri();
        $aTopMenu = array(
            'get-rss' => array('href' => BX_DOL_URL_ROOT . $sBaseUri . 'act_rss/', 'target' => '_blank', 'title' => _t('_' . $sUri . '_get_rss'), 'icon' => BxDolTemplate::getInstance()->getIconUrl('rss.png')),
        );

        return array($this->_oObject->serviceArchiveBlock(), $aTopMenu, array(), true, 'getBlockCaptionMenu');
    }
}
?>
