<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

/**
 * Alert/Handler engine.
 *
 * Is needed to fire some alert(event) in one place and caught it with a handler somewhere else.
 *
 * Related classes:
 *  BxDolAlertsResponse - abstract class for all response classes.
 *  BxDolAlertsResponseUser - response class to process standard profile related alerts.
 *
 * Example of usage:
 * 1. Fire an alert
 *
 * @code
 * $oZ = new BxDolAlerts('unit_name', 'action', 'object_id', 'sender_id', 'extra_params');
 * $oZ->alert();
 * @endcode
 *
 * 2. Add handler and caught alert(s) @see BxDolAlertsResponseUser
 *  a. Create Response class extending BxDolAlertsResponse class. It should process all necessary alerts which are passed to it.
 *  b. Register your handler in the database by adding it in `sys_alerts_handlers` table.
 *  c. Associate necessary alerts with the handler by adding them in the `sys_alerts` table.
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolAlerts extends BxDol {

    var $sUnit;
    var $sAction;
    var $iObject;
    var $iSender;
    var $aExtras;

    var $_aAlerts;
    var $_aHandlers;

    /**
     * Constructor
     * @param string $sType - system type
     * @param string $sAction - system action
     * @param int $iObjectId - object id
     * @param int $iSenderId - sender (action's author) profile id, if it is false - then currectly logged in profile id is used
     */
    function BxDolAlerts($sUnit, $sAction, $iObjectId, $iSender = false, $aExtras = array()) {
        parent::BxDol();

        $oDb = BxDolDb::getInstance();
        $oCache = $oDb->getDbCacheObject();
        $aData = $oCache->getData($oDb->genDbCacheKey('sys_alerts'));
        if (null === $aData)
            $aData = BxDolAlerts::cache();

        $this->_aAlerts = $aData['alerts'];
        $this->_aHandlers = $aData['handlers'];

        $this->sUnit = $sUnit;
        $this->sAction = $sAction;
        $this->iObject = (int)$iObjectId;        
        $this->aExtras = $aExtras;
        if (false === $iSender) {
            bx_import('BxDolProfile');
            $oProfile = BxDolProfile::getInstance();
            $this->iSender = $oProfile ? $oProfile->id() : 0;
        } else {
            $this->iSender = (int)$iSender;
        }
    }

    /**
     * Notifies the necessary handlers about the alert.
     */
    function alert() {

        if (isset($this->_aAlerts[$this->sUnit]) && isset($this->_aAlerts[$this->sUnit][$this->sAction]))
            foreach($this->_aAlerts[$this->sUnit][$this->sAction] as $iHandlerId) {
                $aHandler = $this->_aHandlers[$iHandlerId];

                if (!empty($aHandler['file']) && !empty($aHandler['class']) && file_exists(BX_DIRECTORY_PATH_ROOT . $aHandler['file'])) {
                    if(!class_exists($aHandler['class']))
                        require_once(BX_DIRECTORY_PATH_ROOT . $aHandler['file']);

                    $oHandler = new $aHandler['class']();
                    $oHandler->response($this);
                } else if (!empty($aHandler['eval'])) {
                    eval($aHandler['eval']);
                }
            }
    }

    /**
     * Cache alerts and handlers.
     *
     * @return an array with all alerts and handlers.
     */
    function cache() {
        $oDb = BxDolDb::getInstance();
        $aResult = array('alerts' => array(), 'handlers' => array());

        $aAlerts = $oDb->getAll("SELECT `unit`, `action`, `handler_id` FROM `sys_alerts` ORDER BY `id` ASC");
        foreach ($aAlerts as $aAlert)
            $aResult['alerts'][$aAlert['unit']][$aAlert['action']][] = $aAlert['handler_id'];

        $aHandlers = $oDb->getAll("SELECT `id`, `class`, `file`, `eval` FROM `sys_alerts_handlers` ORDER BY `id` ASC");
        foreach ($aHandlers as $aHandler)
            $aResult['handlers'][$aHandler['id']] = array('class' => $aHandler['class'], 'file' => $aHandler['file'], 'eval' => $aHandler['eval']);

        $oCache = $oDb->getDbCacheObject();
        $oCache->setData ($oDb->genDbCacheKey('sys_alerts'), $aResult);

        return $aResult;
    }
}

class BxDolAlertsResponse extends BxDol {

    function BxDolAlertsResponse() {
        parent::BxDol();
    }

    function response($oAlert) {
    }
}

