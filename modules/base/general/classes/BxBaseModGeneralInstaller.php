<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');
bx_import('BxDolImageTranscoder');
bx_import('BxDolService');

class BxBaseModGeneralInstaller extends BxDolStudioInstaller 
{
    protected $_aTranscoders = array ();
    protected $_bUpdateTimeline = false;

    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
    }

    function enable($aParams) 
    {
        $aResult = parent::enable($aParams);

        if (!$aResult['result']) // proces further only in case of successful enable
            return $aResult;

        BxDolImageTranscoder::registerHandlersArray($this->_aTranscoders);

        if ($this->_bUpdateTimeline && BxDolRequest::serviceExists('timeline', 'add_handlers'))
            BxDolService::call('timeline', 'add_handlers', array($this->_aConfig['home_uri']));

        return $aResult;
    }

    function disable($aParams) 
    {    
        if ($this->_bUpdateTimeline && BxDolRequest::serviceExists('timeline', 'delete_handlers'))
            BxDolService::call('timeline', 'delete_handlers', array($this->_aConfig['home_uri']));

        BxDolImageTranscoder::unregisterHandlersArray($this->_aTranscoders);

        $aResult = parent::disable($aParams);

        if (!$aResult['result']) // we need to register it back if disabling failed
            BxDolImageTranscoder::registerHandlersArray($this->_aTranscoders);

        return $aResult;
    }

    function install($aParams, $bEnable = false) 
    {
        return parent::install($aParams, $bEnable);
    }

    function uninstall($aParams, $bDisable = false) 
    {
        return parent::uninstall($aParams, $bDisable);
    }
}

/** @} */ 
