<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxBaseCmtsForm extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

    	if(isset($this->aInputs['cmt_image'])) {
            $aFormNested = array(
                'params' =>array(
                    'nested_form_template' => 'comments_uploader_nfw.html'
                ),
                'inputs' => array(),
            );

            $oFormNested = new BxDolFormNested('cmt_image', $aFormNested, 'cmt_submit');

            $this->aInputs['cmt_image']['storage_object'] = 'sys_cmts_images';
            $this->aInputs['cmt_image']['images_transcoder'] = 'sys_cmts_images_preview';
            $this->aInputs['cmt_image']['uploaders'] = !empty($this->aInputs['cmt_image']['value']) ? unserialize($this->aInputs['cmt_image']['value']) : array('sys_cmts_simple');
            $this->aInputs['cmt_image']['upload_buttons_titles'] = array('Simple' => 'camera');
            $this->aInputs['cmt_image']['multiple'] = true;
            $this->aInputs['cmt_image']['ghost_template'] = $oFormNested;
        }
    }

	public function getStorageObjectName()
    {
        return isset($this->aInputs['cmt_image']['storage_object']) ? $this->aInputs['cmt_image']['storage_object'] : '';
    }

    public function getTranscoderPreviewName()
    {
    	return isset($this->aInputs['cmt_image']['images_transcoder']) ? $this->aInputs['cmt_image']['images_transcoder'] : '';
    }
}

/** @} */
