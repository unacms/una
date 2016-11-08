<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    SMTPMailer SMTP Mailer
 * @ingroup     UnaModules
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
