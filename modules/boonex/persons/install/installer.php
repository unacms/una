<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');
bx_import('BxDolImageTranscoder');

class BxPersonsInstaller extends BxDolStudioInstaller {

    protected $_aTranscoders = array ('bx_persons_thumb', 'bx_persons_preview');

    function __construct($aConfig) {
        parent::__construct($aConfig);
    }

    function enable($aParams) {
        $aResult = parent::enable($aParams);

        if ($aResult['result']) // register it only in case of successful enable
            BxDolImageTranscoder::registerHandlersArray($this->_aTranscoders);

        return $aResult;
    }

    function disable($aParams) {

        BxDolImageTranscoder::unregisterHandlersArray($this->_aTranscoders);

        $aResult = parent::disable($aParams);

        if (!$aResult['result']) // we need to register it back if disable failed
            BxDolImageTranscoder::registerHandlersArray($this->_aTranscoders);

        return $aResult;
    }

    function install($aParams) {

        return parent::install($aParams);
    }

    function uninstall($aParams) {

        return parent::uninstall($aParams);
    }
}

/** @} */ 
