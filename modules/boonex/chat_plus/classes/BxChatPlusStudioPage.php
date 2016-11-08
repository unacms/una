<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    RocketChat Rocket.Chat integration module
 * @ingroup     UnaModules
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
