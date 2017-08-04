<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
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

        $sTitle = $sUrl = $sIcon = "";
        if ($this->_aContentInfo && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedView($this->_aContentInfo)) {
            $sTitle = isset($this->_aContentInfo[$CNF['FIELD_TITLE']]) ? $this->_aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($this->_aContentInfo[$CNF['FIELD_TEXT']], 20, '...');
            $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aContentInfo[$CNF['FIELD_ID']]);
            $sIcon = $CNF['ICON'];

            $this->addMarkers($this->_aContentInfo); // every field can be used as marker
        }

        $this->addMarkers(array(
            'title' => $sTitle,
            'entry_link' => $sUrl,
        ));

        // select view entry submenu
        $this->_setSubmenu(array(
        	'title' => $sTitle,
			'link' => $sUrl,
            'icon' => $sIcon
        ));
    }

    protected function _setSubmenu($aParams)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
		$oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], array_merge(array(
			'title' => '',
			'link' => '',
			'icon' => '',
		), $aParams));
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
