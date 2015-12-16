<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModConnectTemplate extends BxDolModuleTemplate
{
    protected $_sPageIcon;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function pageCodeAdminStart()
    {
        ob_start();
    }

    function pageCodeAdmin ($sTitle)
    {
        global $_page;
        global $_page_cont;

        $_page['name_index'] = 9;

        $_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
        $_page['header_text'] = $sTitle;

        $_page_cont[$_page['name_index']]['page_main_code'] = ob_get_clean();

        PageCodeAdmin();
    }

    /**
     * Function will generate default dolphin's page;
     *
     * @param  : $sPageCaption   (string) - page's title;
     * @param  : $sPageContent   (string) - page's content;
     * @return : (text) html presentation data;
     */
    function getPage($sPageCaption, $sPageContent)
    {
        global $_page;
        global $_page_cont;

        $_page['name_index'] = 0;

        // set module's icon;
        $GLOBALS['oTopMenu'] -> setCustomSubIconUrl(false === strpos($this->_sPageIcon, '.') ? $this->_sPageIcon : $this -> getIconUrl($this->_sPageIcon));
        $GLOBALS['oTopMenu'] -> setCustomSubHeader($sPageCaption);

        $_page['header'] = $sPageCaption;
        $_page['header_text'] = $sPageCaption;

        $_page_cont[0]['page_main_code'] = $sPageContent;

        PageCode($this);

        exit;
    }
}

/** @} */
