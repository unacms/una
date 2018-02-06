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
    function __construct($oTemplate = null)
    {
        parent::__construct($oTemplate);
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
            $sContent .= $this->_oTemplate->getMenu ('sys_add_content');
            $sContent .= $this->_oTemplate->getMenu ('sys_account_popup');
        }

        return $sContent;
    }
}

/** @} */
