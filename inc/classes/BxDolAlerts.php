<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

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

bx_import('BxDolDb');

class BxDolAlerts extends BxDol
{
    public $sUnit;
    public $sAction;
    public $iObject;
    public $iSender;
    public $aExtras;

    protected $_aAlerts;
    protected $_aHandlers;

    /**
     * Constructor
     * @param string $sType     - system type
     * @param string $sAction   - system action
     * @param int    $iObjectId - object id
     * @param int    $iSenderId - sender (action's author) profile id, if it is false - then currectly logged in profile id is used
     */
    public function __construct($sUnit, $sAction, $iObjectId, $iSender = false, $aExtras = array())
    {
        parent::__construct();

        if (getParam('sys_db_cache_enable')) {

            $oDb = BxDolDb::getInstance();
            $oCache = $oDb->getDbCacheObject();
            $sCacheKey = $oDb->genDbCacheKey('sys_alerts');
            $aData = $oCache->getData($sCacheKey);
            if (null === $aData) {
                $aData = $this->getAlertsData();
                $oCache->setData ($sCacheKey, $aData);
            }

        } else {

            $aData = $this->getAlertsData();

        }

        $this->_aAlerts = $aData['alerts'];
        $this->_aHandlers = $aData['handlers'];

        $this->sUnit = $sUnit;
        $this->sAction = $sAction;
        $this->iObject = (int)$iObjectId;
        $this->aExtras = $aExtras;
        if (false === $iSender) {
            $oProfile = BxDolProfile::getInstance();
            $this->iSender = $oProfile ? $oProfile->id() : 0;
        } else {
            $this->iSender = (int)$iSender;
        }
    }

    public static function cacheInvalidate()
    {
        return BxDolDb::getInstance()->cleanCache ('sys_alerts');
    }

    /**
     * Notifies the necessary handlers about the alert.
     */
    public function alert()
    {
        if (isset($this->_aAlerts[$this->sUnit]) && isset($this->_aAlerts[$this->sUnit][$this->sAction]))
            foreach($this->_aAlerts[$this->sUnit][$this->sAction] as $iHandlerId) {
                $aHandler = $this->_aHandlers[$iHandlerId];

                if (!empty($aHandler['file']) && !empty($aHandler['class']) && file_exists(BX_DIRECTORY_PATH_ROOT . $aHandler['file'])) {
                    if(!class_exists($aHandler['class'], false))
                        require_once(BX_DIRECTORY_PATH_ROOT . $aHandler['file']);

                    $oHandler = new $aHandler['class']();
                    $oHandler->response($this);
                } else if(!empty($aHandler['service_call']) && BxDolService::isSerializedService($aHandler['service_call'])) {
                    $aService = unserialize($aHandler['service_call']);

                    $aParams = array($this);
                    if(isset($aService['params']) && is_array($aService['params']))
                        $aParams = array_merge($aParams, $aService['params']);

                    BxDolService::call($aService['module'], $aService['method'], $aParams, isset($aService['class']) ? $aService['class'] : 'Module');
                }
            }
    }

    /**
     * Cache alerts and handlers.
     *
     * @return an array with all alerts and handlers.
     */
    public function getAlertsData()
    {
        $oDb = BxDolDb::getInstance();
        $aResult = array('alerts' => array(), 'handlers' => array());

        $aAlerts = $oDb->getAll("SELECT `unit`, `action`, `handler_id` FROM `sys_alerts` ORDER BY `id` ASC");
        foreach ($aAlerts as $aAlert)
            $aResult['alerts'][$aAlert['unit']][$aAlert['action']][] = $aAlert['handler_id'];

        $aHandlers = $oDb->getAll("SELECT `id`, `class`, `file`, `service_call` FROM `sys_alerts_handlers` ORDER BY `id` ASC");
        foreach ($aHandlers as $aHandler)
            $aResult['handlers'][$aHandler['id']] = array('class' => $aHandler['class'], 'file' => $aHandler['file'], 'service_call' => $aHandler['service_call']);

        return $aResult;
    }
}

class BxDolAlertsResponse extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function response($oAlert) {}
}

/** @} */
