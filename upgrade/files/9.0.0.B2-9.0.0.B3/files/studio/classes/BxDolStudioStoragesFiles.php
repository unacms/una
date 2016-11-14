<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioStoragesFiles extends BxTemplStudioGridStorages
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_sStorage = 'sys_files';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sType = BX_DOL_STUDIO_STRG_TYPE_FILES;
        $this->_aT = array(
        	'err_files_delete' => '_adm_strg_err_files_delete',
        	'msg_files_delete' => '_adm_strg_msg_files_delete',
        	'txt_files_add_popup' => '_adm_strg_txt_files_add_popup',
        );
    }
}

/** @} */
