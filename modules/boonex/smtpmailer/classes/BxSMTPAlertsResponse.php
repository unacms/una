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
