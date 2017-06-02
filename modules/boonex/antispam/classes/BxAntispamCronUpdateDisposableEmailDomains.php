<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamCronUpdateDisposableEmailDomains extends BxDolCron
{
    function processing()
    {
        $o = bx_instance('BxAntispamDisposableEmailDomains', array(), 'bx_antispam');

        $o->updateList('blacklist', 'https://raw.githubusercontent.com/martenson/disposable-email-domains/master/disposable_email_blacklist.conf');

        // TODO: uncomment after adding interface for whitelisting
        // $o->updateList('whitelist', 'https://raw.githubusercontent.com/martenson/disposable-email-domains/master/whitelist.conf');
    }
}

/** @} */
