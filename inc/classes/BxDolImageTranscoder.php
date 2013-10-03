<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolStorage');
bx_import('BxDolImageTranscoderQuery');

/** 
 * @page objects 
 * @section images_transcoder Images Transcoder
 * @ref BxDolImageTranscoder
 */

/**
 * This class transcodes images on the fly. 
 * Transcoded image is saved in the specified storage engine and next time ready image is served. 
 *
 *
 * To add image transcoder object add record to 'sys_objects_transcoder_images' table:
 * - object - name of the transcoder object, in the format: vendor prefix, underscore, module prefix, underscore, image size name; for example: bx_images_thumb.
 * - storage_object - name of the storage object to store transcoded data, the specified storage object need to be created too, @see BxDolStorage.
 * - source_type - type of the source, where is source image is taken from, available options 'Storage' and 'Folder' for now.
 * - source_params - source_type params, each source_type can have own set of params, please read futher for more info about particular source_types, serialized array of params is stored here.
 * - private - how to store transcoded data:
 *      - no - store transcoded data publicly.
 *      - yes - store transcoded data privately.
 *      - auto - detect automatically, not supported for 'Folder' source type.
 * - atime_tracking - track last access time to the transcoded data, allowed values 0 - disables or 1 - enabled.
 * - atime_pruning - prune transcoded images by last access time, if last access time of the image is older than atime_pruning seconds - it is deleted, it works when atime_tracking is enabled
 * - ts - unix timestamp of the last change of transcoder parameters, if transcoded image is older than this value - image is deleted and transcoded again.
 * 
 *
 * Then you need to add image filters to 'sys_transcoder_images_filters' table:
 * - transcoder_object - name of the transcoded object to apply filter to.
 * - filter - filter name, please read futher for available filters.
 * - filter_params - serialized array of filter params, please read futher for particular filters params.
 * - order - if there are several filters for one object, they will be applied in this order.
 * 
 * 
 * 'Folder' source types:
 * This source type is some folder with original images for the transcoding, the identifier of the image (handler) is file name.
 * The params are the following:
 * - path - path to the folder with original images
 * This source type has some limitation:
 * - automatic detection of private files is not supported
 * - transcoded file is not automaticlaly deleted/renewed if original file is changed 
 *
 *
 * 'Storage' source type:
 * The source of original files is Storage engine, the identifier of the image (handler) is file id.
 * The params are the following:
 * - object - name of the Storage object 
 *
 *
 * Available filters:
 * - Resize - this filter resizes original image, the parameters are the following:
 *     - w - width of resulted image.
 *     - h - height of resulted image.
 *     - square_resize - make resulted image square, even of original image is not square, 'w' and 'h' parameters must be the same.
 *     - force_type - always change type of the image to the specified type: jpg, png, gif.
 * - Grayscale - make image grayscale, there is no parameters for this filter
 *
 *
 * Automatic deletetion of associated data is supported - in the case if original or transcoded file is deleted, 
 * but you need to register alert handlers, just call registerHandlers () function to register handler (for example, during module installation) 
 * and call unregisterHandlers () function to unregister handlers (for example, during module uninstallation)
 * 
 *
 * Example of usage:
 * @code
 * bx_import('BxDolImageTranscoder');
 * $oTranscoder = BxDolImageTranscoder::getObjectInstance('bx_images_thumb'); // change images transcode object name to your own
 * $oTranscoder->registerHandlers(); // make sure to call it only once! before the first usage, no need to call it every time 
 * $sTranscodedImageUrl = $oTranscoder->getImageUrl('my_dog.jpg'); // the name of file, in the case of 'Folder' storage type this is file name
 * echo 'My dog : <img src="' . $sUrl . '" />'; // transcoded(resized and/or grayscaled) image will be shown, according to the specified filters
 * @endcode
 *
 */
class BxDolImageTranscoder extends BxDol implements iBxDolFactoryObject {

    protected $_aObject; ///< object properties
    protected $_oStorage; ///< storage object, transcoded images are stored here
    protected $_oDb; ///< database queries object

    /**
     * constructor
     */
    protected function BxDolImageTranscoder($aObject, $oStorage) {
        parent::BxDol();
        $this->_aObject = $aObject;
        $this->_oStorage = $oStorage;        
        $this->_oDb = new BxDolImageTranscoderQuery($aObject);
    }

    /**
     * Get image transcode object instance.
     * @param $sObject - name of trancode object.
     * @return false on error or instance of BxDolImageTranscoder class.
     */
    static public function getObjectInstance($sObject) {

        if (isset($GLOBALS['bxDolClasses']['BxDolImageTranscoder!'.$sObject]))
            return $GLOBALS['bxDolClasses']['BxDolImageTranscoder!'.$sObject];
 
        // get transcode object
        $aObject = BxDolImageTranscoderQuery::getTranscoderObject($sObject); 
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
        $o = new BxDolImageTranscoder($aObject, $oStorage);

        return ($GLOBALS['bxDolClasses']['BxDolImageTranscoder!'.$sObject] = $o);
    }

    /**
     * Delete outdated transcoed data from all transcoeeed objects, by last access time.
     * It called on cron, usually every day. 
     * @return total number of pruned/deleted files
     */
    static public function pruning () {
        $iCount = 0;
        $aObjects = BxDolImageTranscoderQuery::getTranscoderObjects ();
        foreach ($aObjects as $aObject) {
            if (!$aObject['atime_tracking'] || !$aObject['atime_pruning'])
                continue;
            $oTranscoder = BxDolImageTranscoder::getObjectInstance($aObject['object']);
            if (!$oTranscoder)
                continue;
            $iCount += $oTranscoder->prune();
        }
        return $iCount;
    }

    /**
     * Register handlers array
     * It can be called upon module enable event.
     * @param $mixed array of transcoders objects, or just one object 
     */
    static public function registerHandlersArray ($mixed) {
        self::_registerHandlersArray ($mixed, 'registerHandlers');
    }

    /**
     * Unregister handlers array
     * It can be called upon module disbale event.
     * @param $mixed array of transcoders objects, or just one object 
     */
    static public function unregisterHandlersArray ($mixed) {
        self::_registerHandlersArray ($mixed, 'unregisterHandlers');
    }

    /**
     * Called automatically, upon local(transcoded) file deletetion.
     */
    static public function onAlertResponseFileDeleteLocal ($sObject, $oAlert) {
        $oTranscoder = BxDolImageTranscoder::getObjectInstance($sObject);
        if (!$oTranscoder)
            return;

        if ($oAlert->sAction != 'file_deleted')
            return;
        
        $oTranscoder->onDeleteFileLocal($oAlert->iObject);
    }

    /**
     * Called automatically, upon original file deletetion.
     */
    static public function onAlertResponseFileDeleteOrig ($sObject, $oAlert) {
        $oTranscoder = BxDolImageTranscoder::getObjectInstance($sObject);
        if (!$oTranscoder)
            return;

        if ($oAlert->sAction != 'file_deleted')
            return;

        $oTranscoder->onDeleteFileOrig($oAlert->iObject);
    }

    /**
     * Called automatically, upon local(transcoded) file deletetion.
     */
    public function onDeleteFileLocal($iFileId) {
        return $this->_oDb->deleteFileTraces($iFileId);
    }

    /**
     * Called automatically, upon original file deletetion.
     */
    public function onDeleteFileOrig($mixedHandler) {

        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        return $this->_oStorage->deleteFile($iFileId);
    }

    /**
     * Register necessary alert handlers for automatic deletetion of transcoded data if source file is deleted.
     * Make sure that you call it once, before first usage, for example upon module installation.
     */
    public function registerHandlers () {
        if (!$this->_oDb->registerHandlers ())
            return false;
        return $this->clearCacheDB();
    }

    /**
     * Unregister alert handlers for automatic deletetion of transcoded data if source file is deleted.
     * Make sure that you call it once, for example upon module uninstallation.
     */
    public function unregisterHandlers () {
        if (!$this->_oDb->unregisterHandlers ())
            return false;
        return $this->clearCacheDB();
    }

    /**
     * Get storage object where transcoded data is stored    
     */
    public function getStorage() {
        return $this->_oStorage;
    }

    /**
     * Get transcoded image url. 
     * If transcoded image is ready then direct url to this image is returned. 
     * If there is not transcoded data available special url is returned, upon opening this url - image is transcoed automatically and redirects to the ready transcoed image.
     * @params $mixedHandler - image handler
     * @return image url, or false on error.
     */
    public function getImageUrl($mixedHandler) {

        if ($this->isImageReady($mixedHandler)) {

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

        return BX_DOL_URL_ROOT . 'image_transcoder.php?o=' . $this->_aObject['object'] . '&h=' . $mixedHandler . '&t=' . time();
    }

    /**
     * Check if transcoded data is available. No need to call it directly, it is called automatically when it is needed.
     * @params $mixedHandler - image handler
     * @params $isCheckOutdated - check if transcoded image outdated
     * @return false if there is no ready transcoed image is available or it is outdated, true if image is ready 
     */
    public function isImageReady ($mixedHandler, $isCheckOutdated = true) {
        $sMethodImageReady = 'isImageReady_' . $this->_aObject['source_type'];
        return $this->$sMethodImageReady($mixedHandler, $isCheckOutdated);
    }

    /** 
     * Transcode image, no need to call it directly, it is called automatically when it is needed.
     * @params $mixedHandler - image handler
     * @params $iProfileId - optional profile id, to assign transcoded image to, usually it is NOT assigned to any particular profile, so just leave it default
     * @return true on success, false on error
     */
    public function transcode ($mixedHandler, $iProfileId = 0) {

        $sExtChange = false;

        // create tmp file locally 
        $sMethodStoreFile = 'storeFileLocally_' . $this->_aObject['source_type'];
        $sTmpFile = $this->$sMethodStoreFile($mixedHandler);
        if (!$sTmpFile) 
            return false;
        
        // appply filters to tmp file
        $this->initFilters ();
        foreach ($this->_aObject['filters'] as $aParams) {
            $sMethodFilter = 'applyFilter_' . $aParams['filter'];
            if (!method_exists($this, $sMethodFilter))
                continue;
            if (!$this->$sMethodFilter($sTmpFile, $aParams['filter_params'])) {
                unlink($sTmpFile);
                return false;
            }

            if (!empty($aParams['filter_params']['force_type'])) {
                switch ($aParams['filter_params']['force_type']) {
                    case 'jpeg':
                    case 'jpg':
                        $sExtChange = 'jpg';
                        break;
                    case 'png':
                        $sExtChange = 'png';
                        break;
                    case 'gif':
                        $sExtChange = 'gif';
                        break;
                }
            }
        }

        if ($sExtChange && false !== ($iDotPos = strrpos($sTmpFile, '.'))) {            
            $sExtOld = substr($sTmpFile, $iDotPos+1);
            if ($sExtOld != $sExtChange) {
                $sTmpFileOld = $sTmpFile;
                $sTmpFile = substr_replace ($sTmpFile, $sExtChange, $iDotPos+1, strlen($sExtOld));
                if (!rename($sTmpFileOld, $sTmpFile)) {
                    unlink($sTmpFileOld);
                    return false;
                }
            }

        }

        // store transcoded file in the storage
        $sMethodIsPrivate = 'isPrivate_' . $this->_aObject['source_type'];
        $isPrivate = $this->$sMethodIsPrivate($mixedHandler);
        $iFileId = $this->_oStorage->storeFileFromPath ($sTmpFile, $isPrivate, $iProfileId);
        @unlink($sTmpFile);
        if (!$iFileId)
            return false;                

        if (!$this->_oDb->updateHandler($iFileId, $mixedHandler)) {
            $this->_oStorage->deleteFile($iFileId);
            return false;
        }

        $this->_oStorage->afterUploadCleanup($iFileId, $iProfileId);

        return true;
    }
    

    /**
     * Delete outdated files by last access time.
     * @return number of pruned/deleted files
     */
    public function prune () {
        $aFiles = $this->_oDb->getFilesForPruning();        
        if (!$aFiles)
            return false;
        $iCount = 0;
        foreach ($aFiles as $r)
            $iCount += $this->_oStorage->deleteFile($r['file_id']) ? 1 : 0;
        return $iCount;
    }

    // ---------------------------

    protected function isImageReady_Folder ($mixedHandler, $isCheckOutdated = true) {

        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        $aFile = $this->_oStorage->getFile($iFileId);
        if (!$aFile)
            return false;

        if ($isCheckOutdated) { // warning, $isCheckOutdated is partially supported for Folder source type - file modification is not checked
            if ($this->_aObject['ts'] > $aFile['modified'] || !$this->getFilePath_Folder($mixedHandler)) { // if we changed transcoder object params or original file is deleted
                // delete file, so it will be recreated next time
                if ($this->_oStorage->deleteFile($aFile['id']))
                    return false;
            }
        }

        return true;
    }

    protected function isImageReady_Storage ($mixedHandler, $isCheckOutdated = true) {

        $iFileId = $this->_oDb->getFileIdByHandler($mixedHandler);
        if (!$iFileId)
            return false;

        $aFile = $this->_oStorage->getFile($iFileId);
        if (!$aFile)
            return false;

        if ($isCheckOutdated) {
            $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
            if ($oStorageOriginal) {
                $aFileOriginal = $oStorageOriginal->getFile($mixedHandler);
                if (!$aFileOriginal || $aFileOriginal['modified'] > $aFile['modified'] || $this->_aObject['ts'] > $aFile['modified']) { // if original file was changed OR we changed transcoder object params 
                    // delete file, so it will be recreated next time
                    if ($this->_oStorage->deleteFile($aFile['id']))
                        return false;
                } 
            }
        }

        return true;
    }

    protected function isPrivate_Folder ($mixedHandler) {
        if ('no' == $this->_aObject['private'])
            return false;                
        return true;
    }

    protected function isPrivate_Storage ($mixedHandler) {
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

    protected function getFilePath_Folder ($mixedHandler) {

        $sPath = $this->_aObject['source_params']['path'] . $mixedHandler;
        if (!file_exists($sPath))
            $sPath = BX_DIRECTORY_PATH_ROOT . $sPath;

        if (!file_exists($sPath))
            return false;

        return $sPath;
    }

    protected function storeFileLocally_Folder ($mixedHandler) {

        $sPath = $this->getFilePath_Folder($mixedHandler);
        if (!$sPath)
            return false;

        $sTmpFile = $this->getTmpFilename ($mixedHandler);
        if (!copy($sPath, $sTmpFile))
            return false;

        return $sTmpFile;
    }

    protected function storeFileLocally_Storage ($mixedHandler) {

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

    protected function getTmpFilename ($sOverrideName = false) {
        if ($sOverrideName) {
            // TODO: generate uniq folder for current dolphin instance and image object
            return realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . rand(10000, 99999) . $sOverrideName;
        }
        return tempnam(realpath(sys_get_temp_dir()), 'bxdol');
    }

    protected function applyFilter_Grayscale ($sFile, $aParams) { 
        bx_import ('BxDolImageResize');
        $o = BxDolImageResize::getInstance();
        $o->removeCropOptions ();

        $this->_checkForceType ($o, $aParams);

        if (IMAGE_ERROR_SUCCESS == $o->grayscale($sFile))
            return true;

        return false;
    }

    protected function applyFilter_Resize ($sFile, $aParams) { 
        bx_import ('BxDolImageResize');
        $o = BxDolImageResize::getInstance();
        $o->removeCropOptions ();

        if (isset($aParams['w']) && isset($aParams['h']))
            $o->setSize ($aParams['w'], $aParams['h']);                

        if (isset($aParams['square_resize']) && $aParams['square_resize'])
            $o->setSquareResize (true);
        else
            $o->setSquareResize (false);

        $this->_checkForceType ($o, $aParams);

        if (IMAGE_ERROR_SUCCESS == $o->resize($sFile))
            return true;

        return false;
    }

    protected function _checkForceType ($oImageProcessor, $aParams) { 
        if (empty($aParams['force_type']))
            $aParams['force_type'] = false;

        switch ($aParams['force_type']) {
            case 'jpeg':
            case 'jpg':
                $oImageProcessor->setOutputType(IMAGE_TYPE_JPG);
                break;
            case 'png':
                $oImageProcessor->setOutputType(IMAGE_TYPE_PNG);
                break;
            case 'gif':
                $oImageProcessor->setOutputType(IMAGE_TYPE_GIF);
                break;
            default:
                $oImageProcessor->setOutputType(false);
                break;
        }
    }

    protected function initFilters () {

        if (false !== $this->_aObject['filters']) // filters are already initilized
            return;

        // get transcoder filters
        $aFilters = $this->_oDb->getTranscoderFilters();
        $this->_aObject['filters'] = array();
        foreach ($aFilters as $aFilter) {
            if ($aFilter['filter_params'])
                $aFilter['filter_params'] = unserialize($aFilter['filter_params']);
            $this->_aObject['filters'][] = $aFilter;
        }

    }

    protected function clearCacheDB() {
        $this->_oDb->oParams->clearCache();
        $oCacheDb = $this->_oDb->getDbCacheObject();
        return $oCacheDb->removeAllByPrefix('db_');
    }

    static protected function _registerHandlersArray ($mixed, $sFunc) {
        if (!is_array($mixed))
            $mixed = array($mixed);
        foreach ($mixed as $sObject) {
            $oTranscoder = self::getObjectInstance($sObject);
            if ($oTranscoder)
                $oTranscoder->$sFunc();
        }
    }
}

/** @} */
