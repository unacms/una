<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    LinkedInConnect LinkedIn Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxLinkedinStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        $oModule = BxDolModule::getInstance('bx_linkedin'); 
        return _t('_bx_linkedin_information_block', BX_DOL_URL_ROOT . $oModule -> _oConfig -> getBaseUri() . 'handle');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_linkedin&page=help">' . _t('_sys_connect_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
