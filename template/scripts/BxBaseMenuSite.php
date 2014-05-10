<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Site main menu representation.
 */
class BxBaseMenuSite extends BxTemplMenu 
{

    protected $_sObjectSubmenu = false;
    protected $_mixedMainMenuItemSelected = false;

    public function __construct ($aObject, $oTemplate) 
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode () 
    {
        bx_import('BxTemplSearch');
        $oSearch = new BxTemplSearch();
        $oSearch->setQuickSearch(true);

        $aVars = array (
            'menu' => parent::getCode (),
            'search' => $oSearch->getForm(BX_DB_CONTENT_ONLY) . $oSearch->getResultsContainer(),
        );
        return $this->_oTemplate->parseHtmlByName('menu_site.html', $aVars); 
    }

}

/** @} */
