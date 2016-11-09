<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioStoragesImages extends BxTemplStudioGridStorages
{
	protected $_sTranscoderResize;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sStorage = 'sys_images';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sType = BX_DOL_STUDIO_STRG_TYPE_IMAGES;
        $this->_sTranscoderResize = 'sys_image_resize';

        $this->_aT = array(
        	'err_files_delete' => '_adm_strg_err_images_delete',
        	'msg_files_delete' => '_adm_strg_msg_images_delete',
        	'err_files_resize' => '_adm_strg_err_images_resize',
        	'txt_files_add_popup' => '_adm_strg_txt_images_add_popup',
        	'txt_files_resize_popup' => '_adm_strg_txt_images_resize_popup',
        );
    }
}

/** @} */
