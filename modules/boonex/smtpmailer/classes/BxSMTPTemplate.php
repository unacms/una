<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    SMTPMailer SMTP Mailer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxSMTPTemplate extends BxDolModuleTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
