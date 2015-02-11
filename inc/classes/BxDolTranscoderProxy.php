<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section transcoder_proxy Proxy Transcoder 
 * @ref BxDolTranscoderProxy
 */

/**
 * This transcoder can be used as universal transcoder for videos and images.
 * Depending on original file MIME type it calls the appropriate transcoder.
 * Proxied transcoders names are specified in 'source_params' array:
 * - object: original file storage object
 * - image: image transcoder name
 * - video_poster: video transcoder name for video poster image 
 * - video: video transcoders array to force conversion for
 */
class BxDolTranscoderProxy extends BxDolTranscoder implements iBxDolFactoryObject
{
    protected function __construct($aObject, $oStorage)
    {
        parent::__construct($aObject, $oStorage);
        $this->_oDb = new BxDolTranscoderImageQuery($aObject, false);
        $this->_sQueueTable = $this->_oDb->getQueueTable();
    }

    /**
     * Depending on original file mime type call appropriate transcoder 
     */
    public function getFileUrl($mixedHandler)
    {
        $oStorageOriginal = BxDolStorage::getObjectInstance($this->_aObject['source_params']['object']);
        if (!$oStorageOriginal)
            return false;

        $aFile = $oStorageOriginal->getFile($mixedHandler);
        if (!$aFile)
            return false;

        $sTranscoder = '';
        if (0 === strncmp($aFile['mime_type'], 'image/', 6) && !empty($this->_aObject['source_params']['image'])) {
            $sTranscoder = $this->_aObject['source_params']['image'];
        } 
        elseif (0 === strncmp($aFile['mime_type'], 'video/', 6) && !empty($this->_aObject['source_params']['video_poster'])) {
            $sTranscoder = $this->_aObject['source_params']['video_poster'];

            // if additional video transcoders provided call it to force video conversion
            if (empty($this->_aObject['source_params']['video']))
                continue;

            foreach ($this->_aObject['source_params']['video'] as $sVideoTranscoder) {
                if (!($oTranscoder = BxDolTranscoderVideo::getObjectInstance($sVideoTranscoder)))
                    continue;
                
                $oTranscoder->getFileUrl($mixedHandler);                
            }
        }
    
        if (!$sTranscoder)
            return false;

        if (!($oTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder)))
            return false;
        
        return $oTranscoder->getFileUrl($mixedHandler);
    }
}

/** @} */
