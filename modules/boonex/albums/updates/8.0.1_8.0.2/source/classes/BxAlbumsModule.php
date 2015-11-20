<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');

/**
 * Albums module
 */
class BxAlbumsModule extends BxBaseModTextModule
{
    protected $_aContexts = array('popular', 'public', 'author');

    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceDeleteFileAssociations($iFileId)
    {        
        $CNF = &$this->_oConfig->CNF;

        if (!($aMediaInfo = $this->_oDb->getMediaInfoSimpleByFileId($iFileId))) // file is already deleted
            return true; 
    
        if (!$this->_oDb->deassociateFileWithContent(0, $iFileId))
            return false;

        if (!empty($CNF['OBJECT_VIEWS_MEDIA'])) {
            $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_VOTES_MEDIA'])) {
            $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_COMMENTS_MEDIA'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS_MEDIA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
            $oMetatags->onDeleteContent($aMediaInfo['id']);
        }

        if (!empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
            $oMetatags->onDeleteContent($aMediaInfo['id']);
        }

        return true;
    }

    public function serviceMediaExif ($iMediaId = 0)
    {
        return $this->_serviceTemplateFunc ('mediaExif', $iMediaId, 'getMediaInfoById');
    }

    public function serviceMediaComments ($iMediaId = 0)
    {
        return $this->_entityComments($this->_oConfig->CNF['OBJECT_COMMENTS_MEDIA'], $iMediaId);
    }

    public function serviceMediaAuthor ($iMediaId = 0)
    {
        return $this->_serviceTemplateFunc ('mediaAuthor', $iMediaId, 'getMediaInfoById');
    }

    public function serviceMediaSocialSharing ($iMediaId = 0, $bEnableCommentsBtn = false, $bEnableSocialSharing = true)
    {
        if (!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iMediaId)
            return false;
        $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if (!$aMediaInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;

        return $this->_entitySocialSharing ($iMediaId, 0, $aMediaInfo['file_id'], $aMediaInfo['title'], false, $CNF['OBJECT_IMAGES_TRANSCODER_BIG'], $CNF['OBJECT_VOTES_MEDIA'], $CNF['URI_VIEW_MEDIA'], $bEnableCommentsBtn ? $CNF['OBJECT_COMMENTS_MEDIA'] : '', $bEnableSocialSharing);
    }

    public function serviceMediaView ($iMediaId = 0, $mixedContext = false)
    {
        if (!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iMediaId)
            return false;

        if (!$mixedContext) {
            $mixedContext = bx_process_input(bx_get('context'));
            if (!in_array($mixedContext, $this->_aContexts)) // when no context specified, it is assumed that it is an album context
                $mixedContext = bx_process_input($mixedContext, BX_DATA_INT); // numeric context is reserved for future use
        }

        return $this->_oTemplate->entryMediaView ($iMediaId, $mixedContext);
    }

    public function checkAllowedSetThumb ()
    {
        return CHECK_ACTION_RESULT_NOT_ALLOWED;
    }

    public function serviceBrowseRecentMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('recent', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    public function serviceBrowsePopularMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    public function actionGetSiblingMedia($iMediaId, $mixedContext)
    {
        $aSiblings = false;
        $sErrorMsg = false;
        if (!($aMediaInfo = $this->_oDb->getMediaInfoById((int)$iMediaId))) 
            $sErrorMsg = _t('_sys_txt_error_occured');

        if (empty($sErrorMsg) && !($aContentInfo = $this->_oDb->getContentInfoById($aMediaInfo['content_id'])))
            $sErrorMsg = _t('_sys_txt_error_occured');

        if (empty($sErrorMsg) && (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aContentInfo))))
            $sErrorMsg = $sMsg;

        if (empty($sErrorMsg)) {
            $aSiblings = array (
                'next' => $this->_oTemplate->getNextPrevMedia($aMediaInfo, true, $mixedContext),
                'prev' => $this->_oTemplate->getNextPrevMedia($aMediaInfo, false, $mixedContext),
            );
        }
    
        $a = $sErrorMsg ? array('error' => $sErrorMsg) : array('next' => $aSiblings['next'], 'prev' => $aSiblings['prev']);

        $s = json_encode($a);

        header('Content-type: text/html; charset=utf-8');
        echo $s;
    }

    public function actionRssMedia ()
    {
        $aArgs = func_get_args();
        $this->_rss($aArgs, 'SearchResultMedia');
    }

    protected function _buildRssParams($sMode, $aArgs)
    {        
        if ($aParams = parent::_buildRssParams($sMode, $aArgs))
            return $aParams;

        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'album':
                $aParams = array('album_id' => isset($aArgs[0]) ? (int)$aArgs[0] : '');
                break;
        }

        return $aParams;
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        $CNF = &$this->_oConfig->CNF;

        if (!($aMediaList = $this->_oDb->getMediaListByContentId($aContentInfo[$CNF['FIELD_ID']])))
            return array();

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $aMediaList = array_slice($aMediaList, 0, 3);
        $aOutput = array();
        foreach ($aMediaList as $aMedia) {
            $aOutput[] = array (
                'url' => $this->_oTemplate->getViewMediaUrl($CNF, $aMedia['id']), 
                'src' => $oTranscoder->getFileUrl($aMedia['file_id']),
            );
        }

        return $aOutput;
    }
}

/** @} */
