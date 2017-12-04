<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Module representation.
 */
class BxBaseModGeneralTemplate extends BxDolModuleTemplate
{
    protected $MODULE;
    protected $_oModule;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);        
        $this->addCss ('main.css');
    }
    
    public function getModule()
    {
        if (!$this->_oModule) {
            $sName = $this->_oConfig->getName();
            $this->_oModule = BxDolModule::getInstance($sName);
        }
        return $this->_oModule;
    }

	public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $sBaseUri = $this->_oConfig->getBaseUri();
        $sJsClass = $this->_oConfig->getJsClass($sType);
        $sJsObject = $this->_oConfig->getJsObject($sType);

        $aParams = array_merge(array(
            'sActionUri' => $sBaseUri,
            'sActionUrl' => BX_DOL_URL_ROOT . $sBaseUri,
            'sObjName' => $sJsObject,
        	'aHtmlIds' => array(),
            'oRequestParams' => array()
        ), $aParams);
        $sContent = "var " . $sJsObject . " = new " . $sJsClass . "(" . json_encode($aParams) . ");";

        return !$bWrap ? $sContent : $this->_wrapInTagJsCode($sContent);
    }

    function getUnitMetaItemLink($sContent, $aAttrs = array())
    {
        return $this->getUnitMetaItem('a', $sContent, $aAttrs);
    }

    function getUnitMetaItemText($sContent, $aAttrs = array())
    {
        if(!is_array($aAttrs))
            $aAttrs = array();

        $aAttrs['class'] = (!empty($aAttrs['class']) ? $aAttrs['class'] . ' ' : '') . 'bx-def-font-grayed';

        return $this->getUnitMetaItem('span', $sContent, $aAttrs);
    }

    function getUnitMetaItemCustom($sContent)
    {
        return $this->getUnitMetaItem('custom', $sContent);
    }

	function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aVars = $aData;
        $aVars['entry_title'] = !empty($CNF['FIELD_TITLE']) && isset($aData[$CNF['FIELD_TITLE']]) ? bx_process_output($aData[$CNF['FIELD_TITLE']]) : '';
        $aVars['entry_text'] = !empty($CNF['FIELD_TEXT']) && isset($aData[$CNF['FIELD_TEXT']]) ? bx_process_output($aData[$CNF['FIELD_TEXT']], BX_DATA_HTML) : '';

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);

            // keywords
            if ($oMetatags->keywordsIsEnabled()) {
                $aFields = array_merge($oMetatags->keywordsFields($aData, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW']), array('entry_title', 'entry_text'));
                foreach ($aFields as $sField)
                    $aVars[$sField] = $oMetatags->keywordsParse($aData[$CNF['FIELD_ID']], $aVars[$sField]);
            }

            // location
            $aVars['location'] = $oMetatags->locationsIsEnabled() ? $oMetatags->locationsString($aData[$CNF['FIELD_ID']]) : '';
        }

        if (empty($aVars['entry_text']))
            return false;

        unset($aVars['recipients']);
        
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryLocation ($iContentId)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (empty($CNF['OBJECT_METATAGS']))
            return '';

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);        

        if (!($sLocationString = $oMetatags->locationsString($iContentId)))
            return '';

        $aVars = array (
            'location' => $sLocationString
        );
        return $this->parseHtmlByName('entry-location.html', $aVars);
    }

	public function entryInfo($aData, $aValues = array())
    {
    	$CNF = $this->_oConfig->CNF;
        $aValuesDefault = array();

        if (isset($aData[$CNF['FIELD_ADDED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_created'),
                'value' => bx_time_js($aData[$CNF['FIELD_ADDED']]),
            );

        if (isset($aData[$CNF['FIELD_CHANGED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_updated'),
                'value' => bx_time_js($aData[$CNF['FIELD_CHANGED']]),
            );

        $aValues = array_merge($aValuesDefault, $aValues);

    	return $this->parseHtmlByName('entry-info.html', array(
    		'bx_repeat:info' => $aValues,
    	));
    }

    function entryAllActions ($sActionsEntity, $sActionsSocial)
    {
        if(empty($sActionsEntity) && empty($sActionsSocial))
            return '';

        return $this->parseHtmlByName('entry-all-actions.html', array (
            'actions_entity' => $sActionsEntity,
            'actions_social' => $sActionsSocial,
        ));
    }

    function entryAttachments ($aData, $aParams = array())
    {
        return $this->entryAttachmentsByStorage($this->getModule()->_oConfig->CNF['OBJECT_STORAGE'], $aData, $aParams);
    }

	function entryAttachmentsByStorage ($sStorage, $aData, $aParams = array())
    {
        if (!($a = $this->getAttachments($sStorage, $aData, $aParams)))
            return '';

    	return $this->parseHtmlByName('attachments.html', array(
            'bx_repeat:attachments' => $a,
        ));
    }
    
    protected function getUnitMetaItem($sName, $sContent, $aAttrs = array(), $sTemplate = 'unit_meta_item.html')
    {
        if(empty($sContent))
            return '';

        $aTags = array('span', 'a', 'custom');

        $aTmplVarsAttrs = array();
        foreach($aAttrs as $sKey => $sValue)
            $aTmplVarsAttrs[] = array('key' => $sKey, 'value' => bx_html_attribute($sValue));

        $aTmplVars = array();
        foreach($aTags as $sTag) {
            $aTmplVarsTag = array();
            $bTmplVarsTag = $sTag == $sName;
            if($bTmplVarsTag)
                $aTmplVarsTag = array(
                    'content' => $sContent,
                    'bx_repeat:attrs' => $aTmplVarsAttrs
                );

            $aTmplVars['bx_if:' . $sTag] = array(
            	'condition' => $bTmplVarsTag,
                'content' => $aTmplVarsTag
            );
        }

        return $this->parseHtmlByName($sTemplate, $aTmplVars);
    }

	protected function getAttachments ($sStorage, $aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $oStorage = BxDolStorage::getObjectInstance($sStorage);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $aTranscodersVideo = false;

        if (isset($CNF['OBJECT_VIDEOS_TRANSCODERS']) && $CNF['OBJECT_VIDEOS_TRANSCODERS'])
            $aTranscodersVideo = array (
                'poster' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']),
                'mp4' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']),
                'webm' => BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['webm']),
            );

        $aGhostFiles = $oStorage->getGhosts ($aData[$CNF['FIELD_AUTHOR']], $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        $sFilterField = isset($aParams['filter_field']) ? $aParams['filter_field'] : $CNF['FIELD_THUMB'];
		if(!empty($sFilterField) && isset($aData[$sFilterField]))
	        foreach ($aGhostFiles as $k => $a) {
	            // don't show thumbnail in attachments
	            if ($a['id'] == $aData[$sFilterField])
	                unset($aGhostFiles[$k]);
	        }

        if(!$aGhostFiles)
            return false;

        $aAttachmnts = array();
        foreach ($aGhostFiles as $k => $a) {

            $isImage = $oTranscoder && (0 === strncmp('image/', $a['mime_type'], 6)); // preview for images, transcoder object for preview must be defined
            $isVideo = $aTranscodersVideo && (0 === strncmp('video/', $a['mime_type'], 6)); // preview for videos, transcoder object for video must be defined
            $sUrlOriginal = $oStorage->getFileUrlById($a['id']);
            $sImgPopupId = 'bx-messages-atachment-popup-' . $a['id'];

            // images are displayed with preview and popup upon clicking
            $a['bx_if:image'] = array (
                'condition' => $isImage,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'popup_id' => $sImgPopupId,
                    'url_preview' => $isImage ? $oTranscoder->getFileUrl($a['id']) : '',
                    'popup' =>  BxTemplFunctions::getInstance()->transBox($sImgPopupId, '<img src="' . $sUrlOriginal . '" />', true, true),
                ),
            );

            // videos are displayed inline
            $a['bx_if:video'] = array (
                'condition' => $isVideo,
                'content' => array (
                    'video' => $isVideo && $aTranscodersVideo ? BxTemplFunctions::getInstance()->videoPlayer(
                        $aTranscodersVideo['poster']->getFileUrl($a['id']), 
                        $aTranscodersVideo['mp4']->getFileUrl($a['id']), 
                        $aTranscodersVideo['webm']->getFileUrl($a['id']),
                        false, ''
                    ) : '',
                ),
            );

            // non-images are displayed as text links to original file
            $a['bx_if:not_image'] = array (
                'condition' => !$isImage && !$isVideo,
                'content' => array (
                    'url_original' => $sUrlOriginal,
                    'attr_file_name' => bx_html_attribute($a['file_name']),
                    'file_name' => bx_process_output($a['file_name']),
                ),
            );

            $aAttachmnts[] = $a;
        }

        return $aAttachmnts;
    }
}

/** @} */
