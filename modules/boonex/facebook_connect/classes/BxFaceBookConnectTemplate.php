<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    FacebookConnect Facebook Connect
 * @ingroup     TridentModules
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
