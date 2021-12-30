<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Stream module
 */
class BxStrmModule extends BxBaseModTextModule
{
    protected $_oEngine;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_PUBLISHED'],
            $CNF['FIELD_ANONYMOUS'],
            $CNF['FIELD_ALLOW_COMMENTS']
        ));
    }

    public function getStreamEngine ()
    {
        if ($this->_oEngine)
            return $this->_oEngine;

        $sEngine = getParam('bx_stream_engine');
        bx_import('Engine' . $sEngine, $this->_aModule);
        $sClass = 'BxStrmEngine' . $sEngine;
        $this->_oEngine = new $sClass;
        return $this->_oEngine;
    }

    public function actionNginxOnPublish()
    {
        $sKey = bx_get('name');
        $sSecret = bx_get('s');
        if (empty($sKey) || empty($sSecret) || !($aContentInfo = $this->_oDb->getEntriesBy(['type' => 'conditions', 'conditions' => ['key' => $sKey]]))) {
            header('HTTP/1.0 404 Not Found');
        }
        elseif ($sSecret != base_convert(substr(md5(BX_DOL_SECRET . $sKey), -4), 16, 36)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            header('HTTP/1.0 201 Created');
        }
        exit;
    }

    public function actionEmbedStream($iContentId = 0)
    {
        $this->_serviceTemplateFunc ('embedStream', $iContentId);
    }

    public function actionStreamViewers ($iContentId = 0)
    {
        header('Content-Type:text/javascript; charset=utf-8');
     
        $CNF = &$this->_oConfig->CNF;
        $mixedContent = $this->_getContent($iContentId, 'getContentInfoById');
        if ($mixedContent === false) {
            echo json_encode(['viewers' => _t('_sys_txt_error_occured')]);
            exit;
        }
        list($iContentId, $aContentInfo) = $mixedContent;

        $iNum = $this->getStreamEngine()->getViewersNum($aContentInfo[$CNF['FIELD_KEY']]);
        if (false === $iNum)
            $this->onStreamStopped($iContentId, $aContentInfo);
        else
            $this->onStreamStarted($iContentId, $aContentInfo);
        
        echo json_encode(['viewers' => $iNum !== false ? _t('_bx_stream_txt_viewers', (int)$iNum) : _t('_bx_stream_txt_wait_for_stream'), 'num' => $iNum]);
    }

    public function serviceStreamBroadcast ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryStreamBroadcast', $iContentId);
    }
    public function serviceStreamViewers ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryStreamViewers', $iContentId);
    }

    public function serviceStreamPlayer ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryStreamPlayer', $iContentId);
    }

    public function serviceStreamRecordings ($iContentId = 0)
    {
        $oGrid = BxDolGrid::getObjectInstance('bx_stream_recordings');
        return $oGrid ? $oGrid->getCode() : false;
    }

    public function serviceStreamRtmpSettings ($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        $mixedContent = $this->_getContent($iContentId, 'getContentInfoById');
        if ($mixedContent === false)
            return false;
        list($iContentId, $aContentInfo) = $mixedContent;

        if ($aContentInfo[$CNF['FIELD_AUTHOR']] !== bx_get_logged_profile_id() && !isAdmin()) 
            return false;

        $a = $this->getStreamEngine()->getRtmpSettings($aContentInfo[$CNF['FIELD_KEY']]);
        if (!$a)
            return false;

        $aForm = array(
            'form_attrs' => array(
                'name' => 'bx-stream-stmp-settings',
            ),
            'inputs' => array(
                'url' => array(
                    'type' => 'text',
                    'name' => 'url'.time(),
                    'caption' => _t('_bx_stream_form_entry_input_server'),
                    'value' => $a['server'],
                    'attrs' => array('readonly' => 'readonly'),
                ),
                'key' => array(
                    'type' => 'password',
                    'name' => 'key'.time(),
                    'caption' => _t('_bx_stream_form_entry_input_stream_key'),
                    'value' => $a['key'],
                    'attrs' => array('readonly' => 'readonly'),
                ),
            ),
        );

        if ($this->getStreamEngine()->isSreamFromBrowser()) {
            $sUrl = 'page.php?i=broadcast-stream&id=' . (int)$iContentId;
            $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sUrl);
            $aForm['inputs']['header_div'] = array (
                'type' => 'custom',
                'content' => _t('_bx_stream_manual_settings_or_stream_from_webcam'),
            );
            $aForm['inputs']['stream-from-webcam'] = array (
                'type' => 'submit',
                'name' => 'submit',
                'value' => _t('_bx_stream_from_webcam'),
                'attrs' => array('onclick' => 'document.location="' . bx_html_attribute($sUrl) . '"; return false;'),
            );
        }

        $oForm = new BxTemplFormView ($aForm);
        return $oForm->getCode();
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        return $aResult;
    }

    public function serviceCheckAllowedCommentsPost($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo && $aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;
        
        return parent::serviceCheckAllowedCommentsPost($iContentId, $sObjectComments);
    }
	
	public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo && $aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }

    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function serviceGetBadges($iContentId,  $bIsSingle = false, $bIsCompact  = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $s = parent::serviceGetBadges($iContentId,  $bIsSingle, $bIsCompact);
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return $s;
        return $s . $this->_oTemplate->getLiveBadge($aContentInfo);
    }

    public function onStreamStarted($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oConfig->CNF;
        if (!$aContentInfo)
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo || $aContentInfo[$CNF['FIELD_STATUS']] != 'awaiting')
            return;

        if(!$this->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'active'), array($CNF['FIELD_ID'] => $iContentId))) 
            return;

        if (CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedRecord()) {
            $iRecordingId = $this->_oDb->getNewRecordingId($iContentId);
            $this->getStreamEngine()->startRecording($iRecordingId, $aContentInfo[$CNF['FIELD_KEY']]);
            BxDolSession::getInstance()->setValue('bx-stream-rec-' . $iContentId, $iRecordingId);
        }

        $this->onPublished($iContentId);

        bx_alert($this->getName(), 'publish_succeeded', $aContentInfo[$CNF['FIELD_ID']], $aContentInfo[$CNF['FIELD_AUTHOR']], array(
            'object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'privacy_view' => BX_DOL_PG_ALL,
        ));
    }

    public function onStreamStopped($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if (!$aContentInfo)
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        if (!$aContentInfo || $aContentInfo[$CNF['FIELD_STATUS']] != 'active')
            return;

        if (!$this->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => 'awaiting'), array($CNF['FIELD_ID'] => $iContentId)))
            return;

        if (CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedRecord(true)) {
            $iRecordingId = BxDolSession::getInstance()->getValue('bx-stream-rec-' . $iContentId);
            if (!$iRecordingId)
                $iRecordingId = $this->_oDb->getRecordingId($iContentId);
            if ($iRecordingId) {

                $aRecordings = $this->getStreamEngine()->stopRecording($iRecordingId);
                foreach ($aRecordings as $a) {
                    if ($a && !empty($a['filePath']) && $oStorage = BxDolStorage::getObjectInstance('bx_stream_recordings')) {
                        $iFileId = $oStorage->storeFileFromUrl(getParam('bx_stream_recordings_url') . $a['filePath'], true, 0, $iContentId);
                        if ($iFileId) {
                            $this->_oDb->deleteRecording($iRecordingId);
                        }
                        else {
                            bx_log('bx_stream', "Store recording from URL(" . getParam('bx_stream_recordings_url') . $a['filePath'] . ") failed");
                        }
                    }
                }

                BxDolSession::getInstance()->unsetValue('bx-stream-rec-' . $iContentId);

            }
            else {
                bx_log('bx_stream', "No recording ID defined for content ID($iContentId)");
            }
        }

        if (BxDolRequest::serviceExists('bx_timeline', 'get_all')) {
            $a = BxDolService::call('bx_timeline', 'get_all', array(array(
                'type' => 'conditions', 
                'conditions' => array(
                    'type' => 'bx_stream', 
                    'action' => 'added', 
                    'object_id' => $iContentId
                )
            )));

            if ($a) {
                $oTimeline = BxDolModule::getInstance('bx_timeline');
                if ($oTimeline) {
                    foreach ($a as $r) {
                        // $oTimeline->deleteEvent($r); 
                        // $oTimeline->_oDb->deleteCache(array('event_id' => $r['id']));
                        BxDolService::call('bx_timeline', 'delete_entity', array($r['id']));
                    }
                }
            }
        }
    }

    public function checkAllowedRecord ($isPerformAction = false)
    {
        if (!getParam('bx_stream_recordings_url'))
            return _t('_sys_txt_access_denied');

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'record', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
