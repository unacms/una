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
        $this->_sPageIcon = 'facebook';
    }

    function dislayPageError() 
    {
        $oPage = BxDolPage::getObjectInstance('bx_facebook_error');
        $oTemplate = BxDolTemplate::getInstance();

        if ($oPage) {

            $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
            $oTemplate->setPageContent ('page_main_code', $oPage->getCode());
            $oTemplate->getPageCode();

        } else {

            $oTemplate->displayPageNotFound();
        }
    }
}

/** @} */
