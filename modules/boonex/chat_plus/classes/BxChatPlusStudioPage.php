<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ChatPlus Chat+ module
 * @ingroup     TridentModules
 *
 * @{
 */

class BxChatPlusStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        return _t('_bx_chat_plus_information_block');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_chat_plus&page=help">' . _t('_bx_chat_plus_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
