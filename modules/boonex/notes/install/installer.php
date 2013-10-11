<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');
bx_import('BxDolImageTranscoder');

class BxNotesInstaller extends BxDolStudioInstaller {

    protected $_aTranscoders = array ('bx_notes_preview');

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

    function install($aParams, $bEnable = false) {

        return parent::install($aParams, $bEnable);
    }

    function uninstall($aParams, $bDisable = false) {

        return parent::uninstall($aParams, $bDisable);
    }
}

/** @} */ 
