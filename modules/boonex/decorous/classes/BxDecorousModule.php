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

    function serviceIncludeCssJs($sType)
    {
        $sResult = '';
        if(BxDolTemplate::getInstance()->getCode() != $this->_oConfig->getUri())
            return $sResult;

        switch($sType) {
            case 'head':
                $sCss = trim(getParam($this->_oConfig->getName() . '_styles_custom'));
                if(!empty($sCss))
                    $sResult .= $this->_oTemplate->_wrapInTagCssCode($sCss);

                $sResult .= $this->_oTemplate->addJs([
                    'custom.js'
                ], true);
            break;

            case 'footer':
                $sResult .= $this->_oTemplate->addJs([
                    'modules/base/template/js/|sidebar.js'
                ], true);
                break;
        }

        return $sResult;
    }
}

/** @} */
