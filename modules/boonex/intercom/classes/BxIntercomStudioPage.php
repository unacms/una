<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Intercom Intercom integration module
 * @ingroup     TridentModules
 *
 * @{
 */

class BxIntercomStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        return _t('_bx_intercom_information_block');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_intercom&page=help">' . _t('_bx_intercom_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
