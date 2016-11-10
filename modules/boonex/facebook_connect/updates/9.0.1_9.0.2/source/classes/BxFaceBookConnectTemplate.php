<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    FacebookConnect Facebook Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFaceBookConnectTemplate extends BxBaseModConnectTemplate
{
    function __construct($oConfig, $oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function dislayPageError() 
    {
        $oPage = BxDolPage::getObjectInstance('bx_facebook_error');
        $oTemplate = BxDolTemplate::getInstance();

        if (!$oPage)
            $oTemplate->displayPageNotFound();
        else
            parent::getPage(false, $oPage->getCode());
    }
}

/** @} */
