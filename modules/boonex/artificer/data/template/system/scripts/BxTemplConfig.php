<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplConfig extends BxBaseConfig
{
    protected $_bModule;
    protected $_sModule;

    function __construct()
    {
        $this->_sModule = 'bx_artificer';
        $this->_bModule = BxDolModuleQuery::getInstance()->isModuleByName($this->_sModule);

        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], [
            'bx-font-family' => 'Helvetica, Arial, sans-serif',

            'bx-color-page' => '#edf1f7',
            'bx-color-block' => '#fff',
            'bx-color-box' => 'rgba(218, 248, 255, 1.0)',
            'bx-color-box-hover' => 'rgba(218, 248, 255, 0.5)',
            'bx-color-sec' => '#fff',
            'bx-color-hl' => 'rgba(202, 242, 252, 0.2)',
            'bx-color-active' => 'rgba(202, 242, 252, 0.4)',

            'bx-border-color' => '#00a0ce',
        ]);

        if($this->_bModule) {
            $this->setPageWidth('bx_artificer_page_width');
        }

        BxDolTemplate::getInstance()->addCss([
            'https://rsms.me/inter/inter.css',
            'menu-sidebar.css',
        ]);
    }
}

/** @} */
