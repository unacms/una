<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxAlbumsFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aInfo, $oTemplate);
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        parent::_associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $sData = '';
        $sExif = '';
        $aExif = false;
        $aFile = $oStorage->getFile($iFileId);
        if (0 === strncmp('image/', $aFile['mime_type'], 6)) {
            $oImageReize = BxDolImageResize::getInstance();

            $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_BIG']);            
            $a = $oImageReize->getImageSize($oTranscoder->getFileUrl($iFileId));
            $sData = isset($a['w']) && isset($a['h']) ? $a['w'] . 'x' . $a['h'] : '';

            if ($aExif = $oImageReize->getExifInfo($oStorage->getFileUrlById($iFileId))) {
                $a = array('Make', 'Model', 'FocalLength', 'ShutterSpeedValue', 'ExposureTime', 'ISOSpeedRatings', 'Orientation', 'Artist', 'Copyright', 'Flash', 'WhiteBalance', 'DateTimeOriginal', 'DateTimeDigitized', 'ExifVersion', 'COMPUTED', 'GPSLatitudeRef', 'GPSLatitude', 'GPSLongitudeRef', 'GPSLongitude', 'GPSAltitudeRef', 'GPSAltitude', 'GPSTimeStamp', 'GPSImgDirectionRef', 'GPSImgDirection', 'GPSDateStamp');
                $aExifFiltered = array();
                foreach ($a as $sIndex)
                    if (isset($aExif[$sIndex]))
                        $aExifFiltered[$sIndex] = $aExif[$sIndex];
                $sExif = serialize($aExifFiltered);
            }
        }
        
        if (false === $this->_oModule->_oDb->associateFileWithContent ($iContentId, $iFileId, $iProfileId, $this->getCleanValue('title-' . $iFileId), $sData, $sExif))
            return;

        $aMediaInfo = $this->_oModule->_oDb->getMediaInfoSimpleByFileId($iFileId);

        if ($aMediaInfo && !empty($CNF['OBJECT_METATAGS_MEDIA'])) {            
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
            if ($oMetatags->keywordsIsEnabled())
                $oMetatags->keywordsAdd($aMediaInfo['id'], $aMediaInfo['title']);
        }

        if ($aMediaInfo && isset($aExif['Make']) && !empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
            if ($oMetatags->keywordsIsEnabled())
                $oMetatags->keywordsAddOne($aMediaInfo['id'], $oMetatags->keywordsCameraModel($aExif));
        }

        bx_alert($this->_oModule->getName(), 'media_added', $aMediaInfo['id'], $iProfileId, array('media_info' => $aMediaInfo));
    }

    function _deleteFile ($iFileId, $sStorage = '')
    {
        if (!($bRet = parent::_deleteFile ($iFileId)))
            return false;

        $aMediaInfo = $this->_oModule->_oDb->getMediaInfoSimpleByFileId($iFileId);
        
        $this->_oModule->serviceDeleteFileAssociations ($iFileId);

        if ($aMediaInfo)
            bx_alert($this->_oModule->getName(), 'media_deleted', $aMediaInfo['id'], $aMediaInfo['author'], array('media_info' => $aMediaInfo));
        
        return true;
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        if (!($bRet = parent::delete ($iContentId, $aContentInfo)))
            return $bRet;

        if (!($aMediaList = $this->_oModule->_oDb->getMediaListByContentId($iContentId)))
            return $bRet;

        foreach ($aMediaList as $aMediaInfo) {
            $this->_oModule->serviceDeleteFileAssociations($aMediaInfo['file_id']);
            bx_alert($this->_oModule->getName(), 'media_deleted', $aMediaInfo['id'], $aMediaInfo['author'], array('media_info' => $aMediaInfo));
        }
        
        return $bRet;
    }
}

/** @} */
