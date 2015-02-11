<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_DSG_TYPE_GENERAL', 'general');
define('BX_DOL_STUDIO_DSG_TYPE_LOGO', 'logo');
define('BX_DOL_STUDIO_DSG_TYPE_ICON', 'icon');
define('BX_DOL_STUDIO_DSG_TYPE_SETTINGS', 'settings');

define('BX_DOL_STUDIO_DSG_TYPE_DEFAULT', 'general');

class BxDolStudioDesigner extends BxTemplStudioPage
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('designer');

        $this->oDb = new BxDolStudioDesignerQuery();

        $this->sPage = BX_DOL_STUDIO_DSG_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

        //--- Check actions ---//
        if(($sAction = bx_get('dsg_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_dsg_err_cannot_process_action'));
            switch($sAction) {
                case 'delete_logo':
                    $aResult = array('code' => 0, 'message' => '');
                    if(!$this->deleteLogo())
                        $aResult = array('code' => 2, 'message' => _t('_adm_dsg_err_remove_old_logo'));
                    break;

                case 'make_default':
                    $aResult = array('code' => 0, 'message' => '');
                    if(!$this->makeDefault())
                        $aResult = array('code' => 2, 'message' => _t('_adm_dsg_err_make_default'));
                    break;

                case 'get-page-by-type':
                    $sValue = bx_process_input(bx_get('dsg_value'));
                    if(empty($sValue))
                        break;

                    $this->sPage = $sValue;
                    $aResult = array('code' => 0, 'content' => $this->getPageCode());
                    break;
            }

            echo json_encode($aResult);
            exit;
        }
    }

    function makeDefault()
    {
        $sValue = bx_get('dsg_value');
        if($sValue === false)
            return false;

        $sValue = bx_process_input($sValue);
        return $this->oDb->setParam('template', $sValue);
    }

    function submitLogo(&$oForm)
    {
        $iProfileId = getLoggedId();

        if(!empty($_FILES['image']['tmp_name'])) {
            $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

            if(!$this->deleteLogo())
                return $this->getJsResult('_adm_dsg_err_remove_old_logo');

            $iId = $oStorage->storeFileFromForm($_FILES['image'], false, $iProfileId);
            if($iId === false) {
                $this->oDb->setParam('sys_site_logo', 0);
                return $this->getJsResult(_t('_adm_dsg_err_save') . $oStorage->getErrorString(), false);
            }

            $this->oDb->setParam('sys_site_logo', $iId);
            $oStorage->afterUploadCleanup($iId, $iProfileId);
        }

        $this->oDb->setParam('sys_site_logo_alt', $oForm->getCleanValue('alt'));
        $this->oDb->setParam('sys_site_logo_width', $oForm->getCleanValue('width'));
        $this->oDb->setParam('sys_site_logo_height', $oForm->getCleanValue('height'));
        return $this->getJsResult('_adm_dsg_scs_save', true, true, BX_DOL_URL_STUDIO . 'designer.php?page=' . BX_DOL_STUDIO_DSG_TYPE_LOGO);
    }

    function deleteLogo()
    {
        $iProfileId = getLoggedId();

        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

        $iId = (int)getParam('sys_site_logo');
        if($iId != 0 && !$oStorage->deleteFile($iId, $iProfileId))
            return false;

        $this->oDb->setParam('sys_site_logo', 0);
        return true;
    }

    function submitIcon(&$oForm)
    {
        $iProfileId = getLoggedId();

        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

        $iId = (int)getParam('sys_site_icon');
        if($iId != 0 && !$oStorage->deleteFile($iId, $iProfileId))
            return $this->getJsResult('_adm_dsg_err_remove_old_icon');

        $iId = $oStorage->storeFileFromForm($_FILES['image'], true, $iProfileId);
        if($iId === false) {
            $this->oDb->setParam('sys_site_icon', 0);
            return $this->getJsResult(_t('_adm_dsg_err_save') . $oStorage->getErrorString(), false);
        }

        $this->oDb->setParam('sys_site_icon', $iId);
        $oStorage->afterUploadCleanup($iId, $iProfileId);

        return $this->getJsResult('_adm_dsg_scs_save', true, true, BX_DOL_URL_STUDIO . 'designer.php?page=' . BX_DOL_STUDIO_DSG_TYPE_ICON);
    }
}

/** @} */
