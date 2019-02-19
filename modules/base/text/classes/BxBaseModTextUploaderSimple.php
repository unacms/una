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

class BxBaseModTextUploaderSimple extends BxTemplUploaderSimple
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $sStorageObject, $sUniqId, $this->_oModule->_oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_ENTRY_ATTACHMENTS'])->addMarkers(array(
            'js_object_uploader' . str_replace($this->_sModule, '', $this->_aObject['object']) => $this->getNameJsInstanceUploader()
        ));
    }
}

/** @} */
