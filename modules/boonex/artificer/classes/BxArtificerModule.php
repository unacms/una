<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxBaseModTemplateModule');

class BxArtificerModule extends BxBaseModTemplateModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceIncludeCssJs()
    {
        if(BxDolTemplate::getInstance()->getCode() != $this->_oConfig->getUri())
            return '';

        return $this->_oTemplate->getIncludeCssJs();
    }
}

/** @} */
