<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxGlsrGridCommon extends BxBaseModTextGridCommon
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_glossary';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
