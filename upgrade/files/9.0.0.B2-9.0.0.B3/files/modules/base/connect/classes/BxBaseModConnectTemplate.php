<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModConnectTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function getPage($sPageCaption, $sPageContent)
    {
        $oTemplate = BxDolTemplate::getInstance();

        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        if ($sPageCaption)
            $oTemplate->setPageTitle ($sPageCaption);
        $oTemplate->setPageContent ('page_main_code', $sPageContent);

        $oTemplate->getPageCode();
    }
}

/** @} */
