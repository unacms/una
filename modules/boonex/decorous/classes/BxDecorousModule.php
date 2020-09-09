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
        if(BxDolTemplate::getInstance()->getCode() != $this->_oConfig->getUri())
            return '';
		
    	$sCss = trim(getParam($this->_oConfig->getName() . '_styles_custom'));
        return (!empty($sCss) ? $this->_oTemplate->_wrapInTagCssCode($sCss) : '') . $this->_oTemplate->_wrapInTagJs(BX_DOL_URL_MODULES . 'boonex/decorous/template/js/custom.js');
    }
}

/** @} */
