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

class BxAlbumsCmtsMedia extends BxTemplCmts
{
    protected $MODULE;
    protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_albums';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit);
    }

    public function getObjectTitle ($iObjectId = 0)
    {
        $sResult = parent::getObjectTitle($iObjectId);
        if(empty($sResult))
            $sResult = _t('_bx_albums_txt_media_title_empty');

        return $sResult;
    }

    public function getObjectPrivacyView ($iObjectId = 0)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $iResult = parent::getObjectPrivacyView($iObjectId);

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($this->getId());
        if(empty($aMedia) || !is_array($aMedia))
            return $iResult;

        $aAlbum = $this->_oModule->_oDb->getContentInfoById($aMedia['content_id']);
        if(empty($aAlbum) || !is_array($aAlbum))
            return $iResult;

        return $aAlbum[$CNF['FIELD_ALLOW_VIEW_TO']];
    }

    public function isViewAllowed ($isPerformAction = false)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($this->getId());

        $mixedResult = BxDolService::call($this->_aSystem['module'], 'check_allowed_comments_view', array($aMedia['content_id'], $CNF['OBJECT_COMMENTS']));
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isPostAllowed ($isPerformAction = false)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($this->getId());

    	$mixedResult = BxDolService::call($this->_aSystem['module'], 'check_allowed_comments_post', array($aMedia['content_id'], $CNF['OBJECT_COMMENTS']));
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return $this->checkAction ('comments post', $isPerformAction);
    }
}

/** @} */
