<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplPage');
bx_import('BxDolModule');
bx_import('BxDolMenu');

/**
 * Entry create/edit pages
 */
class BxBaseModTextPageEntry extends BxTemplPage 
{    
    protected static $MODULE;

    protected $_oModule;
    protected $_aContentInfo = false;

    public function __construct($aObject, $oTemplate = false) 
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance(self::$MODULE);
        $CNF = &$this->_oModule->_oConfig->CNF;

        // select view entry submenu
        $oMenuSumbemu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSumbemu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], $CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION']);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iContentId)
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        if ($this->_aContentInfo) {
            $this->addMarkers($this->_aContentInfo); // every field can be used as marker
            $this->addMarkers(array(
                'title' => strmaxtextlen($this->_aContentInfo[$CNF['FIELD_TEXT']], 20, '...'),
            ));
        }

		bx_import('BxDolView');
		BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId)->doView();
    }

    public function getCode () 
    {
        if (!$this->_aContentInfo) { // if entry is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->_oModule->checkAllowedView($this->_aContentInfo))) {
			$this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        $this->_oModule->checkAllowedView($this->_aContentInfo, true);

        return parent::getCode ();
    }

    protected function _getPageCacheParams () 
    {
        return $this->_aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ID']]; // cache is different for every entry
    }
}

/** @} */
