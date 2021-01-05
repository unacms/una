<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModFilesPrivacy extends BxTemplPrivacy
{
    protected $MODULE;
    protected $_oModule;

    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }
}

/** @} */
