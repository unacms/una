<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolDb');

/**
 * @see BxDolImageTranscoder
 */
class BxDolImageTranscoderQuery extends BxDolDb {
    protected $_aObject;

    public function BxDolImageTranscoderQuery($aObject) {
        parent::BxDolDb();
        $this->_aObject = $aObject;
    }

    static public function getTranscoderObject ($sObject) {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_transcoder_images` WHERE `object` = ?", $sObject);
        return $oDb->getRow($sQuery);
    }

    static public function getTranscoderObjects () {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_transcoder_images`");
        return $oDb->getAll($sQuery);
    }

    public function getTranscoderFilters () {
        $sQuery = $this->prepare("SELECT * FROM `sys_transcoder_images_filters` WHERE `transcoder_object` = ? ORDER BY `order` ASC", $this->_aObject['object']);
        return $this->getAll($sQuery);
    }

    public function updateHandler ($iFileId, $mixedHandler) {
        $sUpdateATime = '';
        if ($this->_aObject['atime_tracking']) {
            $iTime = time();
            $sUpdateATime = $this->prepare(", `atime` = ?", $iTime);
        }
        $sQuery = $this->prepare("INSERT INTO `sys_transcoder_images_files` SET `transcoder_object` = ?, `file_id` = ?, `handler` = ? $sUpdateATime 
            ON DUPLICATE KEY UPDATE `file_id` = ? $sUpdateATime", $this->_aObject['object'], $iFileId, $mixedHandler, $iFileId);
        return $this->res($sQuery);
    }

    public function getFileIdByHandler ($mixedHandler) {
        $sQuery = $this->prepare("SELECT `file_id` FROM `sys_transcoder_images_files` WHERE `transcoder_object` = ? AND `handler` = ?", $this->_aObject['object'], $mixedHandler);
        return $this->getOne($sQuery);
    }

    public function updateAccessTime($mixedHandler) {
        $iTime = time();
        $sQuery = $this->prepare("UPDATE `sys_transcoder_images_files` SET `atime` = ? WHERE `transcoder_object` = ? AND `handler` = ?", $iTime, $this->_aObject['object'], $mixedHandler);
        return $this->res($sQuery);
    }

    public function deleteFileTraces($iFileId) { 
        $sQuery = $this->prepare("DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` = ? AND `file_id` = ?", $this->_aObject['object'], $iFileId);
        return $this->res($sQuery);
    }    

    public function getFilesForPruning () {
        if (!$this->_aObject['atime_tracking'] || !$this->_aObject['atime_pruning'])
            continue;
    
        $sQuery = $this->prepare("SELECT * FROM `sys_transcoder_images_files` WHERE `transcoder_object` = ? AND `atime` != 0 AND `atime` < ?", $this->_aObject['object'], time() - $this->_aObject['atime_pruning']);
        return $this->getAll($sQuery);
    }

    public function registerHandlers () {

        if (!$this->registerHandler ('getAlertHandlerNameLocal', 'alert_response_file_delete_local', $this->_aObject['object'], $this->_aObject['storage_object']))
            return false;

        // add handler for original storage engine
        if ('Storage' == $this->_aObject['source_type']) // if original storage is "Storage", not "Folder"
            if (!$this->registerHandler ('getAlertHandlerNameOrig', 'alert_response_file_delete_orig', $this->_aObject['object'], $this->_aObject['source_params']['object']))
                return false;

        return true;
    }

    public function unregisterHandlers () {

        if (!$this->unregisterHandler('getAlertHandlerNameLocal', $this->_aObject['storage_object']))
            return false;

        // remove handler for original storage engine
        if ('Storage' == $this->_aObject['source_type']) // if original storage is "Storage", not "Folder"
            if (!$this->unregisterHandler('getAlertHandlerNameOrig', $this->_aObject['source_params']['object']))
                return false;
        
        return true;
    }    

    protected function registerHandler ($sHandlerNameFunc, $sServiceFunc, $sObject, $sUnit) {

        $sHandlerName = $this->$sHandlerNameFunc ();
        $iHandlerId = $this->getAlertHandlerId ($sHandlerName);
        if ($iHandlerId) // if handler already exists, do nothing
            return true;

        $sServiceCall = serialize(array(
        	'module' => 'system',
        	'method' => $sServiceFunc,
        	'params' => array($sObject),
        	'class' => 'TemplImageServices'
        ));
        $sQuery = $this->prepare("INSERT INTO `sys_alerts_handlers` SET `name` = ?, `service_call` = ?", $sHandlerName, $sServiceCall);
        if (!$this->query($sQuery))
            return false;
        $iHandlerId = $this->lastId();
        $sQuery = $this->prepare("INSERT INTO `sys_alerts` SET `unit` = ?, `action` = ?, `handler_id` = ?", $sUnit, 'file_deleted', $iHandlerId);
        if (!$this->query($sQuery)) {
            $this->unregisterHandlers ();
            return false;
        }

        return true;
    }

    protected function unregisterHandler ($sHandlerNameFunc, $sUnit) {

        $sHandlerName = $this->$sHandlerNameFunc ();
        $iHandlerId = $this->getAlertHandlerId ($sHandlerName);
        if (!$iHandlerId) // if handler is alrady missing, do nothing
            return true;

        $sQuery = $this->prepare("DELETE FROM `sys_alerts` WHERE `unit` = ? AND `action` = ? AND `handler_id` = ?", $sUnit, 'file_deleted', $iHandlerId);
        if (!$this->query($sQuery))
            return false;

        $sQuery = $this->prepare("DELETE FROM `sys_alerts_handlers` WHERE `id` = ?", $iHandlerId);
        return $this->query($sQuery);
    }    

    protected function getAlertHandlerNameLocal () {
        return 'sys_image_transcoder_local_file_delete_' . $this->_aObject['object'];
    }

    protected function getAlertHandlerNameOrig () {
        return 'sys_image_transcoder_orig_file_delete_' . $this->_aObject['object'];
    }

    protected function getAlertHandlerId ($sHandlerName) {
        $sQuery = $this->prepare("SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = ?", $sHandlerName);
        return $this->getOne($sQuery);
    }
}

