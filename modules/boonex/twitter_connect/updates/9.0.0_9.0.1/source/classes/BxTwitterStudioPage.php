<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    TwitterConnect Twitter Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTwitterStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        $oModule = BxDolModule::getInstance('bx_twitter'); 
        return _t('_bx_twitter_information_block', BX_DOL_URL_ROOT . $oModule -> _oConfig -> getBaseUri() . 'handle');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_twitter&page=help">' . _t('_sys_connect_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
