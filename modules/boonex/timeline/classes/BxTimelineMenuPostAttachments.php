<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineMenuPostAttachments extends BxTemplMenu
{
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    public function isMenuItem($sName)
    {
        if(empty($this->_aObject['menu_items'][$sName]) || !is_array($this->_aObject['menu_items'][$sName]))
            return false;

        $aMenuItem = $this->_aObject['menu_items'][$sName];
        if(isset($aMenuItem['active']) && !$aMenuItem['active'])
            return false;

        return $this->_isVisible($aMenuItem);
    }
}

/** @} */
