<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioConfig extends BxBaseConfig
{
    function __construct()
    {
        parent::__construct();

        $this->_aConfig['aLessConfig'] = array_merge($this->_aConfig['aLessConfig'], array(
            'bx-margin' => '24px',
            'bx-margin-sec' => '16px',
            'bx-margin-thd' => '8px',

            'bx-padding' => '24px',
            'bx-padding-sec' => '16px',
            'bx-padding-thd' => '8px',

            'bx-color-page' => '#f0f2f5',
            'bx-border-color-layout' => '#d9d9d9',

            'bx-size-widget' => '128px',
            'bx-round-corners-radius-widget' => '32px',
        ));
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxTemplStudioConfig();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
}

/** @} */
