<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModNotificationsResponse');

class BxNtfsResponse extends BxBaseModNotificationsResponse
{
    public function __construct()
    {
        parent::__construct();

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance('bx_notifications');
    }

    /**
     * Overwritten method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	$iPrivacyView = $this->_getPrivacyView($oAlert->aExtras);
        if($iPrivacyView == BX_DOL_PG_HIDDEN)
            return;

        $aHandler = $this->_oModule->_oConfig->getHandlers($oAlert->sUnit . '_' . $oAlert->sAction);
        switch($aHandler['type']) {
            case BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT:
                $iId = $this->_oModule->_oDb->insertEvent(array(
                    'owner_id' => $oAlert->iSender,
                    'type' => $oAlert->sUnit,
                    'action' => $oAlert->sAction,
                    'object_id' => $oAlert->iObject,
                    'object_privacy_view' => $iPrivacyView,
                    'content' => '',
                ));

				if(!empty($iId))
					$this->_oModule->onPost($iId);

				break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE:
                $this->_oModule->_oDb->updateEvent(array('object_privacy_view' => $iPrivacyView), array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
                break;

            case BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE:
        		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete') {
        			$this->_oModule->_oDb->deleteEvent(array('owner_id' => $oAlert->iObject));
					break;
            	}

                $this->_oModule->_oDb->deleteEvent(array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
                break;
        }
    }
}

/** @} */
