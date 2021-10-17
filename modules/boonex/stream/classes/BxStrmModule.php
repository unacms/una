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
            $CNF['FIELD_DISABLE_COMMENTS']
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
        
        echo json_encode(['viewers' => $iNum !== false ? _t('_bx_stream_txt_viewers', (int)$iNum) : _t('_bx_stream_txt_wait_for_stream'), 'num' => $iNum]);
    }

    public function serviceStreamViewers ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryStreamViewers', $iContentId);
    }

    public function serviceStreamPlayer ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryStreamPlayer', $iContentId);
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
                    'name' => 'url',
                    'caption' => _t('_bx_stream_form_entry_input_server'),
                    'value' => $a['server'],
                ),
                'key' => array(
                    'type' => 'password',
                    'name' => 'key',
                    'caption' => _t('_bx_stream_form_entry_input_stream_key'),
                    'value' => $a['key'],
                ),
            ),
        );
        
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
        if ($aContentInfo && $aContentInfo[$CNF['FIELD_DISABLE_COMMENTS']] == 1)
            return false;
        
        return parent::serviceCheckAllowedCommentsPost($iContentId, $sObjectComments);
    }
	
	public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo && $aContentInfo[$CNF['FIELD_DISABLE_COMMENTS']] == 1)
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }

    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
