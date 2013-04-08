<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioWidgetsQuery');

define('BX_DOL_STUDIO_WS_ENABLED', 1);
define('BX_DOL_STUDIO_WS_DISABLED', 2);

class BxDolStudioWidgets extends BxTemplStudioPage {
    protected $aWidgets;

    function BxDolStudioWidgets($mixedPageName) {
        parent::BxTemplStudioPage($mixedPageName);

        $this->oDb = BxDolStudioWidgetsQuery::getInstance();

        $this->aWidgets = array();

        if(!$this->bPageMultiple)
            $this->oDb->getWidgets(array('type' => 'by_page_id', 'value' => $this->aPage['id']), $this->aWidgets, false);
        else 
            foreach($this->aPage as $sPage => $aPage) {
                $this->aWidgets[$sPage] = array();
                $this->oDb->getWidgets(array('type' => 'by_page_id', 'value' => $aPage['id']), $this->aWidgets[$sPage], false);
            }
    }

    function isEnabled($aWidget) {
        return true;
    }
}
/** @} */