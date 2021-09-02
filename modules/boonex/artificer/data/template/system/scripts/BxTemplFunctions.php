<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplFunctions extends BxBaseFunctions
{
    public function __construct($oTemplate = null)
    {
        parent::__construct($oTemplate);
    }
    
    public function getMainLogo($aParams = array())
    {
        if(!isset($aParams['attrs']))
            $aParams['attrs'] = array();

        $aParams['attrs']['class'] = '';

        return parent::getMainLogo($aParams);
    }
    
    public function TemplPageAddComponent($sKey)
    {
        switch( $sKey ) {
            case 'sys_site_search':
                $oSearch = new BxTemplSearch();
                $oSearch->setLiveSearch(true);
                return $oSearch->getForm(BX_DB_PADDING_DEF, false, true) . $oSearch->getResultsContainer();

            default:
                return parent::TemplPageAddComponent($sKey);
        }
    }    

    protected function getInjFooterPopupMenus() 
    {
        $sContent = '';

        $oSearch = new BxTemplSearch();
        $oSearch->setLiveSearch(true);
        $sContent .= $this->_oTemplate->parsePageByName('search.html', array(
            'search_form' => $oSearch->getForm(BX_DB_CONTENT_ONLY),
            'results' => $oSearch->getResultsContainer(),
        ));

        if(isLogged()) {
            $sContent .= $this->_oTemplate->getMenu('sys_add_content');
            $sContent .= $this->_oTemplate->getMenu('sys_account_popup');
        }

        return $sContent;
    }
}

/** @} */
