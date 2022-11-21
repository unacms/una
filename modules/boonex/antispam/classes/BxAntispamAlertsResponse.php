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

/**
 * alerts handler
 */
class BxAntispamAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('account' == $oAlert->sUnit) {

            switch ($oAlert->sAction) {
                case 'check_login':
                    $oAlert->aExtras['error_msg'] = BxDolService::call('bx_antispam', 'check_login');
                    break;
                case 'check_join';
                    $oAlert->aExtras['error_msg'] = BxDolService::call('bx_antispam', 'check_join', array($oAlert->aExtras['email'], &$oAlert->aExtras['approve']));
                    break;
            }

        } elseif ('system' == $oAlert->sUnit) {
            
            switch ($oAlert->sAction) {
                case 'check_spam_url':
                    $oAlert->aExtras['is_spam'] = BxDolService::call('bx_antispam', 'is_spam_url', array(&$oAlert->aExtras['content']));
                    break;
                case 'check_spam':
                    $oAlert->aExtras['content'] = BxDolService::call('bx_antispam', 'filter_spam', array($oAlert->aExtras['content']));
                    if ((!isset($oAlert->aExtras['type']) || 'textarea' == $oAlert->aExtras['type']) && is_string($oAlert->aExtras['content']))
                        $oAlert->aExtras['is_spam'] = BxDolService::call('bx_antispam', 'is_spam', array(&$oAlert->aExtras['content']));
                    break;
                case 'form_check':
                    BxDolService::call('bx_antispam', 'check_form_for_toxicity', array(&$oAlert->aExtras['object']));
                    break;
                case 'form_submitted':
                    BxDolService::call('bx_antispam', 'on_form_submitted', array($oAlert->aExtras['module'], $oAlert->aExtras['entry_id']));
                    break;
            }
        } elseif ('comment' == $oAlert->sUnit && BxDolService::call('bx_antispam', 'is_last_form_submitted_toxic', array())) {
            BxDolService::call('bx_antispam', 'set_comment_as_pending', array($oAlert->iObject));
        }
    }
}

/** @} */
