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

class BxNtfsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->_sModule = 'bx_notifications';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $sPage);

        $this->aMenuItems = array_merge($this->aMenuItems, array(
            array('name' => BX_BASE_MOD_NTFS_DTYPE_SITE, 'icon' => 'globe', 'title' => '_bx_ntfs_lmi_cpt_site'),
            array('name' => BX_BASE_MOD_NTFS_DTYPE_EMAIL, 'icon' => 'envelope', 'title' => '_bx_ntfs_lmi_cpt_email'),
            array('name' => BX_BASE_MOD_NTFS_DTYPE_PUSH, 'icon' => 'bullhorn', 'title' => '_bx_ntfs_lmi_cpt_push')
        ));
    }

    protected function getSite()
    {
        return $this->_getDeliveryType(BX_BASE_MOD_NTFS_DTYPE_SITE);
    }

    protected function getEmail()
    {
        return $this->_getDeliveryType(BX_BASE_MOD_NTFS_DTYPE_EMAIL);
    }

    protected function getPush()
    {
        return $this->_getDeliveryType(BX_BASE_MOD_NTFS_DTYPE_PUSH);
    }

    protected function _getDeliveryType($sDeliveryType)
    {
        return $this->_oModule->getBlockSettings($sDeliveryType, array(
            'grid' => $this->_oModule->_oConfig->CNF['OBJECT_GRID_SETTINGS_ADMINISTRATION'],
            'template' => BxDolStudioTemplate::getInstance()
        ));
    }
}

/** @} */
