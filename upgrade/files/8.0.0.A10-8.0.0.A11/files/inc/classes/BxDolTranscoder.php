<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_DOL_QUEUE_PENDING', 'pending'); ///< file is pending for processing
define('BX_DOL_QUEUE_PROCESSING', 'processing'); ///< file is in the process
define('BX_DOL_QUEUE_COMPLETE', 'complete'); ///< file is already processed
define('BX_DOL_QUEUE_FAILED', 'failed'); ///< file processing was failed
define('BX_DOL_QUEUE_DELETE', 'delete'); ///< file is subject to delete

/**
 * Base class for @see BxDolTranscoderImage and @see BxDolTranscoderVideo transcoders
 */
class BxDolTranscoder extends BxDol implements iBxDolFactoryObject
{
    protected $_sLog = ''; ///< file conversion log
    protected $_aObject; ///< object properties
    protected $_oStorage; ///< storage object, transcoded files are stored here
    protected $_oDb; ///< database queries object
    protected $_sRetinaSuffix = '@2x'; ///< handler suffix for retina image file
    protected $_sQueueTable = ''; ///< table with queued files for transcoding
    protected $_sQueueStorage = ''; ///< storage object to storage temporary files

    /**
     * constructor
     */
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct();
        $this->_aObject = $aObject;
        $this->_oStorage = $oStorage;
        $this->_oDb = null;
    }

    /**
     * Get transcode object instance.
     * @param $sObject - name of transcode object.
     * @return false on error or instance of transcoder class.
     */
    public static function getObjectInstance($sObject)
    {
        if (isset($GLOBALS['bxDolClasses'][__CLASS__ . '!' . $sObject]))
            return $GLOBALS['bxDolClasses'][__CLASS__ . '!'.$sObject];

        // get transcode object
        $aObject = BxDolTranscoderQuery::getTranscoderObject($sObject);
        if (!$aObject || !is_array($aObject))
            return false;

        if ($aObject['source_params'])
            $aObject['source_params'] = unserialize($aObject['source_params']);

        $aObject['filters'] = false; // filters are initialized on demand

        // create storage object to store transcoded data
        $oStorage = BxDolStorage::getObjectInstance($aObject['storage_object']);
        if (!$oStorage)
            return false;

        // create instance
        $sClass = $aObject['override_class_name'] ? $aObject['override_class_name'] : 'BxDolTranscoderImage';
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass ($aObject, $oStorage);

        return ($GLOBALS['bxDolClasses'][__CLASS__ . '!' . $sObject] = $o);
    }

    /**
     * Delete outdated transcoed data from all transcoeeed objects, by last access time.
     * It called on cron, usually every day.
     * @return total number of pruned/deleted files
     */
    static public function pruning ()
    {
        $iCount = 0;
        $aObjects = self::getTranscoderObjects();
        foreach ($aObjects as $aObject) {
            if (!$aObject['atime_tracking'] || !$aObject['atime_pruning'])
                continue;
            $oTranscoder = self::getObjectInstance($aObject['object']);
            if (!$oTranscoder)
                continue;
            $iCount += $oTranscoder->prune();
        }
        return $iCount;
    }

    /**
     * Get transcoder objects array
     * @return array
     */
    static protected function getTranscoderObjects ()
    {
        return BxDolTranscoderQuery::getTranscoderObjects ();
    }

    /**
     * Process files queued for transcoding
     * Can be performed on separate server.
     */
    static public function processQueue ($bQueuePruning = true)
    {
        if ($bQueuePruning)
            self::pruneQueue();

        $a = BxDolTranscoderQuery::getNextInQueue (gethostname());
        foreach ($a as $r) {
            $o = self::getObjectInstance($r['transcoder_object']);
            $aQueue = $o->getDb()->getFromQueue($r['file_id_source']); 
            if (!$aQueue || $aQueue['status'] != BX_DOL_QUEUE_PENDING)
                continue;
            
            $o->transcode($aQueue['file_id_source'], $aQueue['profile_id']);
        }
    }

    static public function pruneQueue () 
    {
        $a = BxDolTranscoderQuery::getForDeletionFromQueue (gethostname());
        if (!$a)
            return false;

        foreach ($a as $aQueue) {
            $o = self::getObjectInstance($aQueue['transcoder_object']);
            if ($o)
                $o->deleteFromQueue($aQueue['file_id_source']);
        }
    }

    /**
     * Store completed files from tmp queue storage to the final storage
     * Must be performed on the actual site.
     */
    static public function processCompleted ()
    {
        $a = BxDolTranscoderQuery::getCompletedFromQueue ();
        foreach ($a as $r) {
            $o = self::getObjectInstance($r['transcoder_object']);
            $aQueue = $o->getDb()->getFromQueue($r['file_id_source']); 
            if (!$aQueue || $aQueue['status'] != BX_DOL_QUEUE_COMPLETE)
                continue;
            
            if ($aQueue['file_url_result'] && $aQueue['file_id_result']) {
  
                // get transcoded file from provided URL              
                $sFileData = bx_file_get_contents ($aQueue['file_url_result']);
                if (false === $sFileData) {
                    $o->getDb()->updateQueueStatus($aQueue['file_id_source'], BX_DOL_QUEUE_FAILED, "download file failed: {$aQueue['file_url_result']}\n");
                    continue;   
                }
                
                $sTmpFile = $o->getTmpFilename ('.' . $aQueue['file_ext_result']);
                if (!file_put_contents($sTmpFile, $sFileData)) {
                    $o->getDb()->updateQueueStatus($aQueue['file_id_source'], BX_DOL_QUEUE_FAILED, "store downloaded file failed\n");
                    @unlink($sTmpFile);
                    continue;
                }
                
                // store downloaded file in final storage
                if ($o->storeTranscodedFile($aQueue['file_id_source'], $sTmpFile, $aQueue['profile_id']))
                   $o->getDb()->updateQueueStatus($aQueue['file_id_source'], BX_DOL_QUEUE_DELETE); // mark to delete it (deletion is performed on the server where it was transcoded)
                else
                    $o->getDb()->updateQueueStatus($aQueue['file_id_source'], BX_DOL_QUEUE_FAILED, "store file failed:\n" . $o->getLog());

                // delete tmp local file
                @unlink($sTmpFile);
            }
        }
    }

    /**
     * Register handlers array
     * It can be called upon module enable event.
     * @param $mixed array of transcoders objects, or just one object
     */
    static public function registerHandlersArray ($mixed)
    {
        self::_callFuncForObjectsArray ($mixed, 'registerHandlers');
    }

    /**
     * Unregister handlers array
     * It can be called upon module disbale event.
     * @param $mixed array of transcoders objects, or just one object
     */
    static public function unregisterHandlersArray ($mixed)
    {
        self::_callFuncForObjectsArray ($mixed, 'unregisterHandlers');
    }

    /**
     * Cleanup (queue for deletion all resized files)
     * It can be called upon module disbale event.
     * @param $mixed array of transcoders objects, or just one object
     */
    static public function cleanupObjectsArray ($mixed)
    {
        self::_callFuncForObjectsArray ($mixed, 'cleanup');
    }

    /**
     * Called automatically, upon local(transcoded) file deletetion.
     */
    static public function onAlertResponseFileDeleteLocal ($oAlert, $sObject)
    {
        $oTranscoder = self::getObjectInstance($sObject);
        if (!$oTranscoder)
            return;

        if ($oAlert->sAction != 'file_deleted')
            return;

        $oTranscoder->onDeleteFileLocal($oAlert->iObject);
    }

    /**
     * Called automatically, upon original file deletetion.
     */
    static public function onAlertResponseFileDeleteOrig ($oAlert, $sObject)
    {
        $oTranscoder = self::getObjectInstance($sObject);
        if (!$oTranscoder)
            return;

        if ($oAlert->sAction != 'file_deleted')
            return;

        $oTranscoder->onDeleteFileOrig($oAlert->iObject);
    }

    /**
     * Called automatically, upon local(transcoded) file deletetion.
     */
    public function onDeleteFileLocal($iFileId)
    {
        return $this->_oDb->deleteFileTraces($iFileId);
    }

    /**
     * Called automatically, upon original file deletetion.
     */
    public function onDeleteFileOrig($mixedHandler)
    {
        // deleted files queued for transcoding
        $this->_oDb->deleteFromQueue ($mixedHandler);

        // delete main file
        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        if (!$this->_oStorage->deleteFile($iFileId))
            return false;

        // delete retina file
        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler . $this->_sRetinaSuffix);
        if ($iFileId)
            $this->_oStorage->deleteFile($iFileId);

        return true;
    }

    /**
     * Register necessary alert handlers for automatic deletetion of transcoded data if source file is deleted.
     * Make sure that you call it once, before first usage, for example upon module installation.
     */
    public function registerHandlers ()
    {
        if (!$this->_oDb->registerHandlers ())
            return false;
        return $this->clearCacheDB();
    }

    /**
     * Unregister alert handlers for automatic deletetion of transcoded data if source file is deleted.
     * Make sure that you call it once, for example upon module uninstallation.
     */
    public function unregisterHandlers ()
    {
        if (!$this->_oDb->unregisterHandlers ())
            return false;
        return $this->clearCacheDB();
    }

    /**
     * Delete (queue for deletion) all resized files for the current object
     */
    public function cleanup ()
    {
        return $this->_oStorage->queueFilesForDeletionFromObject();
    }

    /**
     * Get storage object where transcoded data is stored
     */
    public function getStorage()
    {
        return $this->_oStorage;
    }

    /**
     * Get database object instance
     */
    public function getDb()
    {
        return $this->_oDb;
    }

    /**
     * Get transcoded file url.
     * If transcoded file is ready then direct url to the file is returned.
     * If there is no transcoded data available, then special url is returned, upon opening this url image is transcoded automatically and redirects to the ready transcoed image.
     * @params $mixedHandler - file handler
     * @return file url, or false on error.
     */
    public function getFileUrl($mixedHandler)
    {
        if ($this->isFileReady($mixedHandler)) {

            $mixedHandler = $this->processHandlerForRetinaDevice($mixedHandler);

            $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
            if (!$iFileId)
                return false;

            $aFile = $this->_oStorage->getFile($iFileId);
            if (!$aFile)
                return false;

            if ($this->_aObject['atime_tracking'])
                $this->_oDb->updateAccessTime($mixedHandler);

            return $this->_oStorage->getFileUrlById($iFileId);
        }

        if ($this->_sQueueTable && !$this->addToQueue($mixedHandler)) // add file to queue (when queue is enabled) if it isn't transcoded yet
            return false;

        return $this->getFileUrlNotReady($mixedHandler);
    }

    /**
     * alias for @see getFileUrl
     */
    public function getFileUrlById($mixedHandler)
    {
        return $this->getFileUrl($mixedHandler);
    }

    /**
     * Check if transcoded data is available. No need to call it directly, it is called automatically when it is needed.
     * @params $mixedHandler - file handler
     * @params $isCheckOutdated - check if transcoded file is outdated
     * @return false if there is no ready transcoded file is available or it is outdated, true if file is ready
     */
    public function isFileReady ($mixedHandler, $isCheckOutdated = true)
    {
        $sMethodFileReady = 'isFileReady_' . $this->_aObject['source_type'];
        return $this->$sMethodFileReady($mixedHandler, $isCheckOutdated);
    }

    /**
     * Transcode file, no need to call it directly, it is called automatically when it is needed.
     * @params $mixedHandler - file handler
     * @params $iProfileId - optional profile id, to assign transcoded file ownership to, usually it is NOT assigned to any particular profile, so just leave it default
     * @return true on success, false on error
     */
    public function transcode ($mixedHandler, $iProfileId = 0)
    {
        $sExtChange = false;

        $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_PROCESSING, '', gethostname());

        // create tmp file locally
        $sSuffix = $this->_sQueueStorage ? 'Queue' : $this->_aObject['source_type'];
        $sMethodStoreFile = 'storeFileLocally_' . $sSuffix;
        $sTmpFile = $this->$sMethodStoreFile($mixedHandler);
        if (!$sTmpFile) {
            $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, "store local tmp file failed ($sMethodStoreFile)");
            return false;
        }

        // appply filters to tmp file
        $this->initFilters ();
        foreach ($this->_aObject['filters'] as $aParams) {
            $this->clearLog();
            $sMethodFilter = 'applyFilter_' . $aParams['filter'];
            if (!method_exists($this, $sMethodFilter))
                continue;
            if (!$this->$sMethodFilter($sTmpFile, $aParams['filter_params'])) {
                unlink($sTmpFile);
                $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, "filter failed: $sMethodFilter\n" . $this->getLog());
                return false;
            }

            if (!empty($aParams['filter_params']['force_type'])) {
                switch ($aParams['filter_params']['force_type']) {
                    case 'jpeg':
                    case 'jpg':
                        $sExtChange = 'jpg';
                        break;
                    default:
                        $sExtChange = $aParams['filter_params']['force_type'];
                }
            }

            if ($sExtChange) {
                $iDotPos = strrpos($sTmpFile, '.');
                $sExtOld = false === $iDotPos ? '' : substr($sTmpFile, $iDotPos+1);
                if ($sExtOld != $sExtChange) {
                    $sTmpFileOld = $sTmpFile;
                    $sTmpFile = false === $iDotPos ? $sTmpFile . '.' . $sExtChange : substr_replace($sTmpFile, $sExtChange, $iDotPos+1, strlen($sExtOld));
                    if (!rename($sTmpFileOld, $sTmpFile)) {
                        @unlink($sTmpFileOld);
                        $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, "change file extension failed: $sTmpFileOld => $sTmpFile");
                        return false;
                    }
                }
            }
        }

        $mixedHandler = $this->processHandlerForRetinaDevice($mixedHandler);
        
        $bRet = true;
        if ($this->_sQueueStorage) {

            $sFileUrlResult = '';
            if (!($iFileIdResult = $this->storeTranscodedFileInQueueStorage($mixedHandler, $sTmpFile, $sFileUrlResult)))
                $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, "store queue tmp file failed:\n" . $this->getLog());
            else
                $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_COMPLETE, '', '', $iFileIdResult, $sFileUrlResult, $sExtChange);

        } else {

            $bRet = $this->storeTranscodedFile($mixedHandler, $sTmpFile, $iProfileId);
            
            if ($bRet)
                $this->deleteFromQueue($mixedHandler);
            else
                $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, "store file failed:\n" . $this->getLog());
                
        }

        @unlink($sTmpFile);

        return $bRet;
    }

    /**
     * Delete outdated files by last access time.
     * @return number of pruned/deleted files
     */
    public function prune ()
    {
        $aFiles = $this->_oDb->getFilesForPruning();
        if (!$aFiles)
            return false;
        $iCount = 0;
        foreach ($aFiles as $r)
            $iCount += $this->_oStorage->deleteFile($r['file_id']) ? 1 : 0;
        return $iCount;
    }

    public function getDevicePixelRatio ()
    {
        if (isset($_COOKIE['devicePixelRatio']) && (!isset($this->_aObject['source_params']['disable_retina']) || !$this->_aObject['source_params']['disable_retina'])) {
            $iDevicePixelRatio = intval($_COOKIE['devicePixelRatio']);
            if ($iDevicePixelRatio >= 2)
                return 2;
        }
        return 1;
    }

    public function getOrigFileUrl ($mixedHandler)
    {
        $sMethodStoreFile = 'getOrigFileUrl_' . $this->_aObject['source_type'];
        return $this->$sMethodStoreFile($mixedHandler);
    }

    /**
     * Get file url when file isn't transcoded yet
     */
    public function getFileUrlNotReady($mixedHandler)
    {
        return false;
    }

    public function getTmpFilename ($sOverrideName = false)
    {
        if ($sOverrideName)
            return BX_DIRECTORY_PATH_TMP . rand(10000, 99999) . $sOverrideName;
        return tempnam(BX_DIRECTORY_PATH_TMP, $this->_aObject['object']);
    }

    public function clearLog()
    {
        $this->_sLog = '';
    }

    public function addToLog($s)
    {
        $this->_sLog .= $s;
    }

    public function getLog()
    {
        return $this->_sLog;
    }

    public function deleteFromQueue ($mixedHandler)
    {
        $sErrMsg = "delete queue tmp file failed:\n";
        $a = $this->_oDb->getFromQueue($mixedHandler);
        if (!$a || $a['server'] != gethostname()) // deletion must be performed on the server where it was transcoded
            return false;
        
        if ($a['file_id_result'] && $this->_sQueueStorage && 0 != strncmp($a['log'], $sErrMsg, strlen($sErrMsg))) {
            $oStorage = BxDolStorage::getObjectInstance($this->_sQueueStorage);
            if (!$oStorage->deleteFile($a['file_id_result'])) {
                $this->_oDb->updateQueueStatus($mixedHandler, BX_DOL_QUEUE_FAILED, $sErrMsg . $this->getLog());
                return false;
            }
        }

        return $this->_oDb->deleteFromQueue($mixedHandler);
    }

    public function storeTranscodedFile ($mixedHandler, $sTmpFile, $iProfileId)
    {
        $sMethodIsPrivate = 'isPrivate_' . $this->_aObject['source_type'];
        $isPrivate = $this->$sMethodIsPrivate($mixedHandler);
        $iFileId = $this->_oStorage->storeFileFromPath ($sTmpFile, $isPrivate, $iProfileId);
        if (!$iFileId) {
            $this->addToLog($this->_oStorage->getErrorString());
            return false;
        }

        if (!$this->_oDb->updateHandler($iFileId, $mixedHandler)) {
            $this->_oStorage->deleteFile($iFileId);
            return false;
        }

        $this->_oStorage->afterUploadCleanup($iFileId, $iProfileId);

        return true;
    }

    protected function storeTranscodedFileInQueueStorage ($mixedHandler, $sTmpFile, &$sFileUrlResult)
    {
        if (!$this->_sQueueStorage)
            return false;

        $oStorage = BxDolStorage::getObjectInstance($this->_sQueueStorage);

        $iFileIdResult = $oStorage->storeFileFromPath ($sTmpFile, 1, 0);
        if (!$iFileIdResult) {
            $this->addToLog($oStorage->getErrorString());
            return false;
        }

        if (!($sFileUrlResult = $oStorage->getFileUrlById($iFileIdResult))) {
            $this->_oStorage->deleteFile($iFileIdResult);
            return false;
        }

        $oStorage->afterUploadCleanup($iFileIdResult, 0);

        return $iFileIdResult;
    }

    protected function addToQueue($mixedHandler, $iProfileId = 0)
    {
        if ($this->_oDb->getFromQueue($mixedHandler))
            return true;
        $sFileUrl = $this->getOrigFileUrl($mixedHandler);
        return $this->_oDb->addToQueue ($mixedHandler, $sFileUrl, $iProfileId);
    }

    protected function applyFilter_Resize ($sFile, $aParams)
    {
        $o = BxDolImageResize::getInstance();
        $o->removeCropOptions ();

        $o->setSize (isset($aParams['w']) ? round($aParams['w'] * $this->getDevicePixelRatio()) : null, isset($aParams['h']) ? round($aParams['h'] * $this->getDevicePixelRatio()) : null);

        if (isset($aParams['crop_resize']) && $aParams['crop_resize'])
            $o->setAutoCrop (true);
        elseif (isset($aParams['square_resize']) && $aParams['square_resize'])
            $o->setSquareResize (true);
        else
            $o->setSquareResize (false);

        if (IMAGE_ERROR_SUCCESS == $o->resize($sFile))
            return true;

        return false;
    }

    protected function isFileReady_Folder ($mixedHandlerOrig, $isCheckOutdated = true)
    {
        $mixedHandler = $this->processHandlerForRetinaDevice($mixedHandlerOrig);

        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        $aFile = $this->_oStorage->getFile($iFileId);
        if (!$aFile)
            return false;

        if ($isCheckOutdated) { // warning, $isCheckOutdated is partially supported for Folder source type - file modification is not checked
            if ($this->_aObject['ts'] > $aFile['modified'] || !$this->getFilePath_Folder($mixedHandlerOrig)) { // if we changed transcoder object params or original file is deleted
                // delete file, so it will be recreated next time
                if ($this->_oStorage->deleteFile($aFile['id']))
                    return false;
            }
        }

        return true;
    }

    protected function isFileReady_Storage ($mixedHandlerOrig, $isCheckOutdated = true)
    {
        $mixedHandler = $this->processHandlerForRetinaDevice($mixedHandlerOrig);

        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        $aFile = $this->_oStorage->getFile($iFileId);
        if (!$aFile)
            return false;

        if ($isCheckOutdated) {
            $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
            if ($oStorageOriginal) {
                $aFileOriginal = $oStorageOriginal->getFile($mixedHandlerOrig);
                if (!$aFileOriginal || $aFileOriginal['modified'] > $aFile['modified'] || $this->_aObject['ts'] >= $aFile['modified']) { // if original file was changed OR we changed transcoder object params
                    // delete file, so it will be recreated next time
                    if ($this->_oStorage->deleteFile($aFile['id']))
                        return false;
                }
            }
        }

        return true;
    }

    protected function isPrivate_Folder ($mixedHandler)
    {
        if ('no' == $this->_aObject['private'])
            return false;
        return true;
    }

    protected function isPrivate_Storage ($mixedHandler)
    {
        switch ($this->_aObject['private']) {
            case 'no':
                return false;
            case 'yes':
                return true;
            default:
            case 'auto':
                $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
                if (!$oStorageOriginal)
                    return true; // in case of error - make sure that file is not public accidentally
                $aFile = $oStorageOriginal->getFile($mixedHandler);
                return $oStorageOriginal->isFilePrivate($aFile['id']);
        }
    }

    protected function getFilePath_Folder ($mixedHandler)
    {
        $sPath = $this->_aObject['source_params']['path'] . $mixedHandler;
        if (!file_exists($sPath))
            $sPath = BX_DIRECTORY_PATH_ROOT . $sPath;

        if (!file_exists($sPath))
            return false;

        return $sPath;
    }

    protected function storeFileLocally_Folder ($mixedHandler)
    {
        $sPath = $this->getFilePath_Folder($mixedHandler);
        if (!$sPath)
            return false;

        $sTmpFile = $this->getTmpFilename ($mixedHandler);
        if (!copy($sPath, $sTmpFile))
            return false;

        return $sTmpFile;
    }

    protected function storeFileLocally_Storage ($mixedHandler)
    {
        $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
        if (!$oStorageOriginal)
            return false;

        $aFile = $oStorageOriginal->getFile($mixedHandler);
        if (!$aFile)
            return false;

        $sUrl = $oStorageOriginal->getFileUrlById($mixedHandler);
        if (!$sUrl)
            return false;

        $sFileData = bx_file_get_contents ($sUrl);
        if (false === $sFileData)
            return false;

        $sTmpFile = $this->getTmpFilename ($aFile['file_name']);
        if (!file_put_contents($sTmpFile, $sFileData))
            return false;

        return $sTmpFile;
    }

    protected function storeFileLocally_Queue ($mixedHandler)
    {   
        $aQueue = $this->_oDb->getFromQueue($mixedHandler);
        if (!$aQueue || !$aQueue['file_url_source'])
            return false;

        $sFileData = bx_file_get_contents ($aQueue['file_url_source']);
        if (false === $sFileData)
            return false;

        $sTmpFile = $this->getTmpFilename ($mixedHandler);
        if (!file_put_contents($sTmpFile, $sFileData))
            return false;

        return $sTmpFile;
    }

    protected function getOrigFileUrl_Folder ($mixedHandler)
    {
        $sPath = $this->_aObject['source_params']['path'] . $mixedHandler;
        if (!file_exists(BX_DIRECTORY_PATH_ROOT . $sPath))
            return false;
        
        return BX_DOL_URL_ROOT . $sPath;
    }

    protected function getOrigFileUrl_Storage ($mixedHandler)
    {
        $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
        if (!$oStorageOriginal)
            return false;

        return $oStorageOriginal->getFileUrlById($mixedHandler);
    }

    protected function initFilters ()
    {
        if (false !== $this->_aObject['filters']) // filters are already initilized
            return;

        // get transcoder filters
        $aFilters = $this->_oDb->getTranscoderFilters();
        $this->_aObject['filters'] = array();
        foreach ($aFilters as $aFilter) {
            $aFilter['filter_params'] = $aFilter['filter_params'] ? unserialize($aFilter['filter_params']) : array();
            $this->_aObject['filters'][] = $aFilter;
        }

    }

    protected function clearCacheDB()
    {
        $oCacheDb = $this->_oDb->getDbCacheObject();
        return $oCacheDb->removeAllByPrefix('db_');
    }

    public function getDevicePixelRatioHandlerSuffix ()
    {
        $iDevicePixelRatio = $this->getDevicePixelRatio ();
        if ($iDevicePixelRatio >= 2)
            return $this->_sRetinaSuffix;
        return '';
    }

    protected function processHandlerForRetinaDevice ($mixedHandler)
    {
        return '' . $mixedHandler . $this->getDevicePixelRatioHandlerSuffix ();
    }

    static protected function _callFuncForObjectsArray ($mixed, $sFunc)
    {
        if (!is_array($mixed))
            $mixed = array($mixed);
        foreach ($mixed as $sObject) {
            $oTranscoder = self::getObjectInstance($sObject);
            if ($oTranscoder)
                $oTranscoder->$sFunc();
        }
    }

    /**
     * Get filter params
     * @param $sName filter name
     * @return array of filter params if filter was found, or false if given filter isn't enabled for the transcoder
     */
    protected function getFilterParams ($sName)
    {
        $this->initFilters ();
        foreach ($this->_aObject['filters'] as $aParams)
            if ($sName == $aParams['filter'])
                return $aParams['filter_params'];
        return false;
    }
}

/** @} */
