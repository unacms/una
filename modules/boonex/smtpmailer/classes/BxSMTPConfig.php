<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    SMTPMailer SMTP Mailer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxSMTPConfig extends BxDolModuleConfig 
{
	function __construct($aModule) 
    {
	    parent::__construct($aModule);
    }   
}

/** @} */
