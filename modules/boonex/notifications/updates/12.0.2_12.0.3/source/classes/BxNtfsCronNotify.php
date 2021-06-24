<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsCronNotify extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    protected $_iAddThreshold;

    public function __construct()
    {
    	$this->_sModule = 'bx_notifications';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    public function processing()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iCount = (int)$this->_oModule->_oDb->queueGet(array('type' => 'count'));
        if($iCount > $CNF['PARAM_QUEUE_ADD_THRESHOLD'])
            return;

        $aEvents = $this->_oModule->_oDb->getEventsToProcess();

        foreach($aEvents as $aEvent) {
            if(!empty($aEvent['content']) && is_string($aEvent['content']))
                $aEvent['content'] = unserialize($aEvent['content']);

            $this->_sendNotifications($aEvent);
        }
    }

    protected function _sendNotifications(&$aEvent)
    {
        $aHandler = $this->_oModule->_oConfig->getHandlers($aEvent['type'] . '_' . $aEvent['action']);
        if(empty($aHandler) || !is_array($aHandler))
            return;

        $aDeliveryTypes = array();

        $iId = (int)$aEvent['id'];
        $iSilentMode = $this->_oModule->getSilentMode($aEvent['content']);
        switch($iSilentMode) {
            case BX_BASE_MOD_NTFS_SLTMODE_ABSOLUTE:
            case BX_NTFS_SLTMODE_ABSOLUTE:
            case BX_NTFS_SLTMODE_SITE:
                return;

            case BX_NTFS_SLTMODE_SITE_EMAIL:
                $aDeliveryTypes[] = BX_BASE_MOD_NTFS_DTYPE_EMAIL;
                break;

            case BX_NTFS_SLTMODE_SITE_PUSH:
                $aDeliveryTypes[] = BX_BASE_MOD_NTFS_DTYPE_PUSH;
                break;

            default:
                $aDeliveryTypes = array(BX_BASE_MOD_NTFS_DTYPE_EMAIL, BX_BASE_MOD_NTFS_DTYPE_PUSH);
        }

        $aSendUsing = array();
        foreach($aDeliveryTypes as $sDeliveryType) {
            $aHidden = $this->_oModule->_oConfig->getHandlersHidden($sDeliveryType);
            if(in_array($aHandler['id'], $aHidden))
                continue;

            $sMethodPostfix = bx_gen_method_name($sDeliveryType);
            $sMethodGet = 'getNotification' . $sMethodPostfix;
            $sMethodSend = 'sendNotification' . $sMethodPostfix;
            if(!$this->_oModule->_oTemplate->isMethodExists($sMethodGet) || !method_exists($this->_oModule, $sMethodSend))
                continue;

            $aSendUsing[$sDeliveryType] = array(
                'method_get' => $sMethodGet,
            	'method_send' => $sMethodSend,
            );
        }

        if(empty($aSendUsing) || !is_array($aSendUsing))
            return;

        $iOwner = (int)$aEvent['owner_id'];
        $aRecipients = array();

        //--- Get recipients: Subscribers.
        $oConnection = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->getObject('conn_subscriptions'));
        $aSubscribers = $oConnection->getConnectedInitiators($iOwner);
        if(!empty($aSubscribers) && is_array($aSubscribers)) {
            $oOwner = BxDolProfile::getInstance($iOwner);
            if(!empty($oOwner)) {
                $sSettingType = bx_srv($oOwner->getModule(), 'act_as_profile') ? BX_NTFS_STYPE_FOLLOW_MEMBER : BX_NTFS_STYPE_FOLLOW_CONTEXT;

                foreach($aSubscribers as $iSubscriber) 
                    $this->_addRecipient($iSubscriber, $sSettingType, $aRecipients);
            }
        }

        //--- Get recipients: Content owner.
        $iObjectOwner = (int)$aEvent['object_owner_id'];
        if($iOwner != $iObjectOwner)
            $this->_addRecipient($iObjectOwner, BX_NTFS_STYPE_PERSONAL, $aRecipients);

        $bDeliveryTimeout = $this->_oModule->_oConfig->getDeliveryTimeout() > 0;

        //--- Check recipients and send notifications.
        $oPrivacyInt = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->getObject('privacy_view'));
        $oPrivacyExt = $this->_oModule->_oConfig->getPrivacyObject($aEvent['type'] . '_' . $aEvent['action']);
        foreach($aRecipients as $iRecipient => $aSettingTypes) {
            $iIdRead = $this->_oModule->_oDb->getLastRead($iRecipient);
            if($iIdRead >= $iId)
                continue;

            $oProfile = BxDolProfile::getInstance($iRecipient);
            if(!$oProfile)
                continue;

            if(!bx_srv($oProfile->getModule(), 'act_as_profile'))
                continue;

            if($oPrivacyExt !== false && !$oPrivacyExt->check($aEvent['id'], $iRecipient)) 
                continue;

            if($oPrivacyInt !== false && !$oPrivacyInt->check($aEvent['id'], $iRecipient))
                continue;

            foreach($aSendUsing as $sDeliveryType => $aDeliveryType)
                foreach($aSettingTypes as $sSettingType) {
                    $aSetting = $this->_oModule->_oDb->getSetting(array('by' => 'tsu_allowed', 'handler_id' => $aHandler['id'], 'delivery' => $sDeliveryType, 'type' => $sSettingType, 'user_id' => $iRecipient));
                    if(empty($aSetting) || !is_array($aSetting))
                        continue;

                    if((int)$aSetting['active_adm'] == 0 || (int)$aSetting['active_pnl'] == 0)
                        continue;

                    $mixedNotification = $this->_oModule->_oTemplate->{$aDeliveryType['method_get']}($iRecipient, $aEvent);
                    if($mixedNotification === false)
                        continue;
            
                    /**
                     * 'break' is essential in the next two conditions to avoid 
                     * duplicate sending to the same recipient.
                     */
                    if($bDeliveryTimeout && $this->_oModule->_oDb->queueAdd(array(
                        'profile_id' => $iRecipient, 
                        'event_id' => $aEvent['id'], 
                        'delivery' => $sDeliveryType,
                        'content' => serialize($mixedNotification),
                        'date' => time()
                    )) !== false)
                        break;

                    if($this->_oModule->{$aDeliveryType['method_send']}($oProfile, $mixedNotification) !== false)
                        break;
                }
        }
    }

    protected function _addRecipient($iUser, $sSettingType, &$aRecipients)
    {
        if(!isset($aRecipients[$iUser]))
            $aRecipients[$iUser] = array();

        $aRecipients[$iUser][] = $sSettingType;
    }
}

/** @} */
