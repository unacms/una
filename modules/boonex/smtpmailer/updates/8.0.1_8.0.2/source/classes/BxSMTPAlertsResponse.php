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

/**
 * alerts handler
 */
class BxSMTPAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('system' != $oAlert->sUnit || 'before_send_mail' != $oAlert->sAction || 'on' != getParam('bx_smtp_on'))
            return;

        $a = $oAlert->aExtras;
        $a['override_result'] = BxDolService::call('bx_smtp', 'send', array($a['email'], $a['subject'], $a['body'], $a['header'], $a['params'], $a['html'], $a['recipient']));
    }
}

/** @} */
