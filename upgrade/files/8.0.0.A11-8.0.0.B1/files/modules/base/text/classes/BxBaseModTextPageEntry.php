<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxBaseModTextPageEntry extends BxBaseModGeneralPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iContentId)
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        if ($this->_aContentInfo) {
            $sTitle = isset($this->_aContentInfo[$CNF['FIELD_TITLE']]) ? $this->_aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($this->_aContentInfo[$CNF['FIELD_TEXT']], 20, '...');
            $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aContentInfo[$CNF['FIELD_ID']]);
            $this->addMarkers($this->_aContentInfo); // every field can be used as marker
            $this->addMarkers(array(
                'title' => $sTitle,
                'entry_link' => $sUrl,
            ));

            // select view entry submenu
            $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
            $oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], array (
                'title' => $sTitle,
                'link' => $sUrl,
                'icon' => $CNF['ICON'],
            ));
        }
    }

    protected function _getBlockService ($aBlock)
    {
        $a = parent::_getBlockService ($aBlock);
        $sTest = '_view_entry_comments';
        if (false !== strpos($aBlock['content'], 'entity_comments') && substr_compare($this->_sObject, $sTest, strlen($this->_sObject) - strlen($sTest), strlen($sTest)) === 0)
            unset($a['title']);            
        return $a;
    }    

}

/** @} */
