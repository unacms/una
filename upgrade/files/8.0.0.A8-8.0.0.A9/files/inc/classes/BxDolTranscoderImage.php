<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolTranscoder');
bx_import('BxDolTranscoderImageQuery');

/**
 * @page objects
 * @section transcoder_images Images Transcoder
 * @ref BxDolTranscoderImage
 */

/**
 * This class transcodes images on the fly.
 * Transcoded image is saved in the specified storage engine and next time ready image is served.
 *
 *
 * To add image transcoder object add record to 'sys_objects_transcoder' table:
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
 * Then you need to add image filters to 'sys_transcoder_filters' table:
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
 *     - crop_resize - crop image to destination size with filling whole area of destination size.
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
 * bx_import('BxDolTranscoderImage');
 * $oTranscoder = BxDolTranscoderImage::getObjectInstance('bx_images_thumb'); // change images transcode object name to your own
 * $oTranscoder->registerHandlers(); // make sure to call it only once! before the first usage, no need to call it every time
 * $sTranscodedImageUrl = $oTranscoder->getFileUrl('my_dog.jpg'); // the name of file, in the case of 'Folder' storage type this is file name
 * echo 'My dog : <img src="' . $sUrl . '" />'; // transcoded(resized and/or grayscaled) image will be shown, according to the specified filters
 * @endcode
 *
 */
class BxDolTranscoderImage extends BxDolTranscoder implements iBxDolFactoryObject
{
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct($aObject, $oStorage);
        $this->_oDb = new BxDolTranscoderImageQuery($aObject, false);
        $this->_sQueueTable = $this->_oDb->getQueueTable();
    }

    /**
     * Get file url when file isn't transcoded yet
     */
    public function getFileUrlNotReady($mixedHandler)
    {
        return BX_DOL_URL_ROOT . 'image_transcoder.php?o=' . $this->_aObject['object'] . '&h=' . $mixedHandler . '&dpx=' . $this->getDevicePixelRatio() . '&t=' . time();
    }

    protected function applyFilter_Grayscale ($sFile, $aParams)
    {
        bx_import ('BxDolImageResize');
        $o = BxDolImageResize::getInstance();
        $o->removeCropOptions ();

        $this->_checkForceType ($o, $aParams);

        if (IMAGE_ERROR_SUCCESS == $o->grayscale($sFile))
            return true;

        return false;
    }

    protected function applyFilter_Resize ($sFile, $aParams)
    {
        bx_import ('BxDolImageResize');
        $o = BxDolImageResize::getInstance();
        $o->removeCropOptions ();

        if (isset($aParams['w']) && isset($aParams['h']))
            $o->setSize ($aParams['w'] * $this->getDevicePixelRatio(), $aParams['h'] * $this->getDevicePixelRatio());

        if (isset($aParams['crop_resize']) && $aParams['crop_resize']) {
            $o->setAutoCrop (true);
        } elseif (isset($aParams['square_resize']) && $aParams['square_resize']) {
            $o->setSquareResize (true);
        } else {
            $o->setSquareResize (false);
        }

        $this->_checkForceType ($o, $aParams);

        if (IMAGE_ERROR_SUCCESS == $o->resize($sFile))
            return true;

        return false;
    }

    protected function _checkForceType ($oImageProcessor, $aParams)
    {
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
}

/** @} */
