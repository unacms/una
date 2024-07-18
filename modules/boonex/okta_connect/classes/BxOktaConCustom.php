<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OktaConnect Okta Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOktaConCustom
{
    function __construct($aModule)
    {
    }

    function onConfig ($oConfig)
    {
    }

    function onRegister ($aRemoteProfileInfo)
    {
        bx_log('bx_oktacon', $aRemoteProfileInfo);
    }

    function onLogin ($oProfile, $aRemoteProfileInfo)
    {
        bx_log('bx_oktacon', $aRemoteProfileInfo);
    }

    function onConvertRemoteFields($aProfileInfo, &$aProfileFields)
    {

    }
}

/** @} */
