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
