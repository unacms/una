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

class BxAlbumsContentInfoMedia extends BxDolContentInfo
{
    protected $MODULE;
	protected $_oModule;

    protected function __construct($sSystem)
    {
        $this->MODULE = 'bx_albums';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem);
    }  

    public function getContentAuthor ($iContentId)
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return 0;

        return $aMedia['author'];
    }

    public function getContentDateAdded ($iContentId)
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return 0;

        return $aMedia['added'];
    }

    public function getContentDateChanged ($iContentId)
    {
        return 0;
    }

    public function getContentTitle ($iContentId)
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return '';

        return $aMedia['title'];
    }

    public function getContentThumb ($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return '';

        $sTranscoder = $CNF['OBJECT_TRANSCODER_BROWSE'];
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);
        if(!$oTranscoder)
            return '';

        return $oTranscoder->getFileUrl($aMedia['file_id']);
    }

    public function getContentLink ($iContentId)
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return '';

        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oModule->_oConfig->CNF['URI_VIEW_MEDIA'] . '&id=' . $iContentId);
    }

    public function getContentText ($iContentId)
    {
        return '';
    }

    public function getContentInfo ($iContentId, $bSearchableFieldsOnly = true)
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return array();

        return $aMedia;
    }

    public function getContentSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        $aMedia = $this->_oModule->_oDb->getMediaInfoById($iContentId);
        if(empty($aMedia) || !is_array($aMedia))
            return '';

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit_media.html';

        return $this->_oTemplate->unitMedia($aMedia, true, $sUnitTemplate);
    }

    public function getAll ($aParams = array())
    {
        if(empty($aParams) || !is_array($aParams))
            $aParams = array('type' => 'all');

        return $this->_oDb->getMediaBy($aParams);
    }
}

/** @} */
