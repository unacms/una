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
bx_import('BxDolStorage');
bx_import('BxDolImageTranscoder');
bx_import('BxDolService');

class BxBaseModGeneralInstaller extends BxDolStudioInstaller 
{
    protected $_aStorages = array (); // storage objects to automatically delete files from upon module uninstallation, don't add storage objects used in image transcoder objects
    protected $_aTranscoders = array (); // image transcoder objects to automatically register/unregister necessary alerts for
    protected $_aConnections = array (); // connections objects associated with module data, it must be defined which content is associated with the connection, the key is connection object name and value is array (possible array values: type, conn, table, field_id), if 'type' == 'profiles', then it is considered profiles connection and other possible param is 'conn' ('initiator', 'content' or 'both') when 'type' == 'custom' (or ommited), then other possible params are 'conn', 'table' and 'field_id' 

    protected $_bUpdateTimeline = false; // set to true to automatically register handlers for timeline module

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
        BxDolImageTranscoder::cleanupObjectsArray($this->_aTranscoders);

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
        // check if module is already waiting while files are deleting
        bx_import('BxDolInstallerUtils');
        if (BxDolInstallerUtils::isModulePendingUninstall($this->_aConfig['home_uri']))
        	return array(
                'message' => _t('_adm_err_modules_pending_uninstall_already'),
                'result' => false,
            );

        // queue for deletion storage files
        $bSetModulePendingUninstall = false;
        foreach ($this->_aStorages as $s) {
            if (($o = BxDolStorage::getObjectInstance($s)) && $o->queueFilesForDeletionFromObject())
                $bSetModulePendingUninstall = true;
        }

        // delete comments and queue for deletion comments attachments
        bx_import('BxDolCmts');
        $iFiles = 0;
        BxDolCmts::onModuleUninstall ($this->_aConfig['name'], $iFiles);
        if ($iFiles)
            $bSetModulePendingUninstall = true;

        // if some files were added to the queue, set module as pending uninstall
        if ($bSetModulePendingUninstall) {
            BxDolInstallerUtils::setModulePendingUninstall($this->_aConfig['home_uri']);
        	return array(
                'message' => _t('_adm_err_modules_pending_uninstall'),
                'result' => false,
            );
        }
                
        // delete associated connections
        if ($this->_aConnections)
            bx_import('BxDolConnection');
        foreach ($this->_aConnections as $sObjectConnections => $a) {
            $o = BxDolConnection::getObjectInstance($sObjectConnections);
            if (!$o)
                continue;

            $sFuncSuffix = 'DeleteInitiatorAndContent';
            if ('initiator' == $a['conn'])
                $sFuncSuffix = 'DeleteInitiator';
            elseif ('content' == $a['conn'])
                $sFuncSuffix = 'DeleteContent';

            if ('profiles' == $a['type']) {
                $sFunc = 'onModuleProfile' . $sFuncSuffix;
                $o->$sFunc($this->_aConfig['name']);
            } else {
                $sFunc = 'onModule' . $sFuncSuffix;
                $o->$sFunc($a['table'], $a['field_id']);
            }
        }

        return parent::uninstall($aParams, $bDisable);
    }
}

/** @} */ 
