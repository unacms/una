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

class BxMarkerIoTemplate extends BxDolModuleTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getIncludeCode()
    {
        $CNF = &$this->_oConfig->CNF;

        $aTmplVarsShowForLogged = array();
        if(isLogged()) {
            $oProfile = BxDolProfile::getInstance();

            $aTmplVarsShowForLogged = array(
                'email' => $oProfile->getAccountObject()->getEmail(),
                'name' => $oProfile->getDisplayName(),
                'profile_url' => $oProfile->getUrl(),
                'template' => $this->getCode(),
            );
        }

        return $this->parseHtmlByName('code.html', array(
            'code' => $this->_oDb->getParam($CNF['PARAM_CODE']),
            'bx_if:show_for_logged' => array(
                'condition' => !empty($aTmplVarsShowForLogged),
                'content' => $aTmplVarsShowForLogged
            )
        ));
    }
}

/** @} */
