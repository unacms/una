<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLabel extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;

    protected function __construct()
    {
        parent::__construct();

        $this->_oDb = new BxDolLabelQuery();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLabel();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getLabels($aParams = array())
    {
        return $this->_oDb->getLabels($aParams);
    }

    public function onAdd($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'added', $iId, false, array('label' => $aLabel));
    }

    public function onEdit($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'edited', $iId, false, array('label' => $aLabel));
    }

    public function onDelete($iId)
    {
        $aLabel = $this->_oDb->getLabels(array('type' => 'id', 'id' => $iId));
        if(empty($aLabel) || !is_array($aLabel))
            return;

        bx_alert('label', 'deleted', $iId, false, array('label' => $aLabel));
    }
}

/** @} */
