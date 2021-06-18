<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Mailchimp Mailchimp integration module
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMailchimpAlerts extends BxDolAlertsResponse
{
    protected $_oModule;
    function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance('bx_mailchimp');
    }

    public function response($o)
    {
        if ('system' == $o->sUnit && 'save_setting' == $o->sAction && 'bx_mailchimp_option_list_id' == $o->aExtras['option']) {
            $this->_oModule->serviceUpdateMergeFields();
        }
        
        if ('account' == $o->sUnit && 'delete' == $o->sAction) {
            $this->_oModule->serviceRemoveAccount($o->iObject);
        }

        if ('account' == $o->sUnit && ('edited' == $o->sAction || 'switch_context' == $o->sAction || 'confirm' == $o->sAction || 'unconfirm' == $o->sAction)) {
            $this->_oModule->serviceUpdateAccount($o->iObject);
        }
        
        if ('profile' == $o->sUnit && ('delete' == $o->sAction || 'edit' == $o->sAction || 'add' == $o->sAction)) {
            $oProfile = BxDolProfile::getInstance($o->iObject);
            $oAccount = $oProfile ? $oProfile->getAccountObject() : null;
            
            if ($oAccount) {
                if ('delete' == $o->sAction && isset($o->aExtras['type']) && 'system' == $o->aExtras['type'])
                    $this->_oModule->serviceRemoveAccount($oAccount->id());
                else
                    $this->_oModule->serviceUpdateAccount($oAccount->id());
            }
        }
    }    
}

/** @} */
