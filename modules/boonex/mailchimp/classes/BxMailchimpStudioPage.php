<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Mailchimp Mailchimp integration module
 * @ingroup     TridentModules
 *
 * @{
 */

class BxMailchimpStudioPage extends BxTemplStudioModule
{
    function getHelp ()
    {
        $oModule = BxDolModule::getInstance('bx_mailchimp');
        return _t('_bx_mailchimp_information_block', BX_DOL_URL_ROOT . $oModule->_oConfig->getBaseUri() . 'bulk_add', BX_MAILCHIMP_LIMIT);
    }

    protected function getPageCaptionHelp()
    {        
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_mailchimp&page=help">' . _t('_bx_mailchimp_information') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
