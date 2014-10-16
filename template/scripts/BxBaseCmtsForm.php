<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplFormView');

class BxBaseCmtsForm extends BxTemplFormView
{
	protected $_sStorageObject;
    protected $_sTranscoderPreview;
    protected $_aImageUploaders;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sStorageObject = 'sys_cmts_images';
        $this->_sTranscoderPreview = 'sys_cmts_images_preview';

        $this->_aImageUploaders = array('sys_cmts_simple');

    	if(isset($this->aInputs['cmt_image'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'comments_uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            bx_import('BxDolFormNested');
            $oFormNested = new BxDolFormNested('cmt_image', $aFormNested, 'cmt_submit');

            $this->aInputs['cmt_image']['storage_object'] = $this->_sStorageObject;
            $this->aInputs['cmt_image']['images_transcoder'] = $this->_sTranscoderPreview;
            $this->aInputs['cmt_image']['uploaders'] = $this->_aImageUploaders;
            $this->aInputs['cmt_image']['upload_buttons_titles'] = array('Simple' => 'camera');
            $this->aInputs['cmt_image']['multiple'] = true;
            $this->aInputs['cmt_image']['ghost_template'] = $oFormNested;
        }
    }

	public function getStorageObjectName()
    {
        return $this->_sStorageObject;
    }

    public function getTranscoderPreviewName()
    {
    	return $this->_sTranscoderPreview;
    }
}

/** @} */
