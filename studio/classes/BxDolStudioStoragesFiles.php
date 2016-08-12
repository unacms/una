<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
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
        	'err_files_add' => '_adm_strg_err_files_add',
        	'err_files_delete' => '_adm_strg_err_files_delete',
        	'msg_files_delete' => '_adm_strg_msg_files_delete',
        	'txt_files_add_popup' => '_adm_strg_txt_files_add_popup',
        );
    }
}

/** @} */
