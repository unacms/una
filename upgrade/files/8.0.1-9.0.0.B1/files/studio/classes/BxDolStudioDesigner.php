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
define('BX_DOL_STUDIO_DSG_TYPE_COVER', 'cover');
define('BX_DOL_STUDIO_DSG_TYPE_SPLASH', 'splash');
define('BX_DOL_STUDIO_DSG_TYPE_SETTINGS', 'settings');

define('BX_DOL_STUDIO_DSG_TYPE_DEFAULT', 'general');

class BxDolStudioDesigner extends BxTemplStudioPage
{
    protected $sPage;

    protected $sManageUrl;

    protected $sParamLogo;
    protected $sParamLogoAlt;
    protected $sParamLogoWidth;
    protected $sParamLogoHeight;

    protected $aCovers;

    function __construct($sPage = "")
    {
        parent::__construct('designer');

        $this->oDb = new BxDolStudioDesignerQuery();

        $this->sPage = BX_DOL_STUDIO_DSG_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

		$this->sManageUrl = BX_DOL_URL_STUDIO . 'designer.php';

		$this->sParamLogo = 'sys_site_logo';
		$this->sParamLogoAlt = 'sys_site_logo_alt';
		$this->sParamLogoWidth = 'sys_site_logo_width';
		$this->sParamLogoHeight = 'sys_site_logo_height';

		$this->aCovers = array(
        	'cover_common' => array('setting' => 'sys_site_cover_common', 'title' => '_adm_dsg_txt_cover_common'),
        	'cover_home' => array('setting' => 'sys_site_cover_home', 'title' => '_adm_dsg_txt_cover_home')
        );
    }

    function checkAction()
    {
    	$sAction = bx_get('dsg_action');
    	if($sAction === false)
    		return;

    	$sAction = bx_process_input($sAction);

		$aResult = array('code' => 1, 'message' => _t('_adm_dsg_err_cannot_process_action'));
		switch($sAction) {
			case 'delete_logo':
				$aResult = array('code' => 0, 'message' => '');
				if(!$this->deleteLogo())
					$aResult = array('code' => 2, 'message' => _t('_adm_dsg_err_remove_old_logo'));
				break;

			case 'delete_cover':
				$sValue = bx_process_input(bx_get('dsg_value'));

				$aResult = array('code' => 0, 'message' => '');
				if(empty($sValue) || !$this->deleteCover($sValue))
					$aResult = array('code' => 2, 'message' => _t('_adm_dsg_err_remove_old_cover'));
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
	function setManageUrl($sUrl)
	{
		$this->sManageUrl = $sUrl;
	}
	function setLogoParams($aParams)
	{
		list($this->sParamLogo, $this->sParamLogoAlt, $this->sParamLogoWidth, $this->sParamLogoHeight) = $aParams;
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
                $this->oDb->setParam($this->sParamLogo, 0);
                return $this->getJsResult(_t('_adm_dsg_err_save') . $oStorage->getErrorString(), false);
            }

            $this->oDb->setParam($this->sParamLogo, $iId);
            $oStorage->afterUploadCleanup($iId, $iProfileId);
        }

        $this->oDb->setParam($this->sParamLogoAlt, $oForm->getCleanValue('alt'));
        $this->oDb->setParam($this->sParamLogoWidth, $oForm->getCleanValue('width'));
        $this->oDb->setParam($this->sParamLogoHeight, $oForm->getCleanValue('height'));
        return $this->getJsResult('_adm_dsg_scs_save', true, true, bx_append_url_params($this->sManageUrl, array('page' => BX_DOL_STUDIO_DSG_TYPE_LOGO)));
    }

    function deleteLogo()
    {
        $iProfileId = getLoggedId();

        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

        $iId = (int)getParam($this->sParamLogo);
        if($iId != 0 && !$oStorage->deleteFile($iId, $iProfileId))
            return false;

        $this->oDb->setParam($this->sParamLogo, 0);
        return true;
    }

	function deleteCover($sCover)
    {
        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

        $iId = (int)getParam($this->aCovers[$sCover]['setting']);
        if($iId != 0 && !$oStorage->deleteFile($iId, getLoggedId()))
            return false;

        $this->oDb->setParam($this->aCovers[$sCover]['setting'], 0);
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

	function submitCover(&$oForm)
    {
        $iProfileId = getLoggedId();

        $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

        foreach($this->aCovers as $sCover => $aCover) {
        	if(empty($_FILES[$sCover]['tmp_name']))
        		continue;

			$iId = (int)getParam($aCover['setting']);
			if($iId != 0 && !$oStorage->deleteFile($iId, $iProfileId))
				return $this->getJsResult('_adm_dsg_err_remove_old_cover');

			$iId = $oStorage->storeFileFromForm($_FILES[$sCover], true, $iProfileId);
			if($iId === false) {
				$this->oDb->setParam($aCover['setting'], 0);
				return $this->getJsResult(_t('_adm_dsg_err_save') . $oStorage->getErrorString(), false);
			}

			$this->oDb->setParam($aCover['setting'], $iId);
			$oStorage->afterUploadCleanup($iId, $iProfileId);
        }

		return $this->getJsResult('_adm_dsg_scs_save', true, true, BX_DOL_URL_STUDIO . 'designer.php?page=' . BX_DOL_STUDIO_DSG_TYPE_COVER);
    }

    function submitSplash(&$oForm)
    {
    	$this->oDb->setParam('sys_site_splash_code', $oForm->getCleanValue('code'));
        $this->oDb->setParam('sys_site_splash_enabled', $oForm->getCleanValue('enabled') == 'on' ? 'on' : '');

        return $this->getJsResult('_adm_dsg_scs_save', true, true, BX_DOL_URL_STUDIO . 'designer.php?page=' . BX_DOL_STUDIO_DSG_TYPE_SPLASH);
    }
}

/** @} */
