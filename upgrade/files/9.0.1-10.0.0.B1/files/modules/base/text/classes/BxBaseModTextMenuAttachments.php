<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create\edit entry attachments menu
 */
class BxBaseModTextMenuAttachments extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
 
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->addMarkers(array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('poll')
        ));
    }
}

/** @} */
