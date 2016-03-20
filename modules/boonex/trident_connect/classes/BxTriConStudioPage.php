<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentConnect Trident Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxTriConStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        return _t('_bx_tricon_information_block', BX_DOL_URL_ROOT);
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_tricon&page=help">' . _t('_bx_tricon_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
