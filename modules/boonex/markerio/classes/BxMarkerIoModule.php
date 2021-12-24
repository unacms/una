<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Marker.io Marker.io
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarkerIoModule extends BxDolModule
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * SERVICE METHODS
     */
    public function serviceIncludeCode()
    {
        if(BxDolTemplate::getInstance()->getPageNameIndex() != 0) 
            return '';

        return $this->_oTemplate->getIncludeCode();
    }
}

/** @} */
