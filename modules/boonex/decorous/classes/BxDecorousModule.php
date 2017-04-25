<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Decorous Decorous template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxBaseModTemplateModule');

class BxDecorousModule extends BxBaseModTemplateModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

	function serviceIncludeCssJs()
    {
    	$sCss = trim(getParam($this->_oConfig->getName() . '_styles_custom'));
        return !empty($sCss) ? $this->_oTemplate->_wrapInTagCssCode($sCss) : '';
    }
}

/** @} */
