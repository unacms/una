<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormEntry');

/**
 * Create/Edit entry form
 */
class BxTimelineFormPost extends BxBaseModGeneralFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_timeline';

        parent::__construct($aInfo, $oTemplate);

        $iUserId = $this->_oModule->getUserId();
        $iOwnerId = $this->_oModule->getOwnerId();

		$this->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'post/';
        $this->aInputs['owner_id']['value'] = $iOwnerId;

        if(isset($this->aInputs['link']))
            $this->aInputs['link']['content'] = $this->_oModule->_oTemplate->getAttachLinkField($iUserId);

        if(isset($this->aInputs['photo'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            bx_import('BxDolFormNested');
            $oFormNested = new BxDolFormNested('photo', $aFormNested, 'do_submit', $this->_oModule->_oTemplate);

            $this->aInputs['photo']['storage_object'] = $this->_oModule->_oConfig->getObject('storage_photos');
            $this->aInputs['photo']['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_photos_preview');
            $this->aInputs['photo']['uploaders'] = $this->_oModule->_oConfig->getUploaders('photo');
            $this->aInputs['photo']['upload_buttons_titles'] = array('Simple' => 'camera');
            $this->aInputs['photo']['multiple'] = true;
            $this->aInputs['photo']['ghost_template'] = $oFormNested;
        }

	    if(isset($this->aInputs['video'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            bx_import('BxDolFormNested');
            $oFormNested = new BxDolFormNested('video', $aFormNested, 'do_submit', $this->_oModule->_oTemplate);

            $this->aInputs['video']['storage_object'] = $this->_oModule->_oConfig->getObject('storage_videos');
            $this->aInputs['video']['images_transcoder'] = $this->_oModule->_oConfig->getObject('transcoder_videos_poster');
            $this->aInputs['video']['uploaders'] = $this->_oModule->_oConfig->getUploaders('video');
            $this->aInputs['video']['upload_buttons_titles'] = array('Simple' => 'video-camera');
            $this->aInputs['video']['multiple'] = true;
            $this->aInputs['video']['ghost_template'] = $oFormNested;
        }

        if(isset($this->aInputs['attachments']))
            $this->aInputs['attachments']['content'] = '__attachments_menu__';
    }

    public function getCode($bDynamicMode = false)
    {
    	$sResult = parent::getCode($bDynamicMode);

    	return $this->_oModule->_oTemplate->parseHtmlByContent($sResult, array(
    		'attachments_menu' => $this->_oModule->getAttachmentsMenuObject()->getCode()
    	));
    }
}

/** @} */
