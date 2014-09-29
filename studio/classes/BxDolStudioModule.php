<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioModulesQuery');

define('BX_DOL_STUDIO_MOD_TYPE_SETTINGS', 'settings');

define('BX_DOL_STUDIO_MOD_TYPE_DEFAULT', BX_DOL_STUDIO_MOD_TYPE_SETTINGS);

class BxDolStudioModule extends BxTemplStudioPage
{
    protected $sModule;
    protected $aModule;
    protected $sPage;
    protected $sPageDefault = BX_DOL_STUDIO_MOD_TYPE_DEFAULT;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule);

        $this->oDb = new BxDolStudioModulesQuery();
        $this->sPageRssHelpObject = 'sys_studio_module_help'; 

        $this->sModule = '';
        if(is_string($sModule) && !empty($sModule))
            $this->sModule = $sModule;

        $this->sPage = $this->sPageDefault;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

	public static function getObjectInstance($sModule = "", $sPage = "", $bInit = true)
	{
	    bx_import('BxDolModuleQuery');
	    $oModuleDb = BxDolModuleQuery::getInstance();

	    bx_import('BxTemplStudioModule');
        $sClass = 'BxTemplStudioModule';
	    if($sModule != '' && $oModuleDb->isModuleByName($sModule)) {
	        $aModule = $oModuleDb->getModuleByName($sModule);

	        if(file_exists(BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'classes/' . $aModule['class_prefix'] . 'StudioPage.php')) {
	            bx_import('StudioPage', $aModule);
	            $sClass = $aModule['class_prefix'] . 'StudioPage';
	        }
	    }

	    $oObject = new $sClass($sModule, $sPage);
	    if($bInit)
	    	$oObject->init();

	    return $oObject;
	}

    public function init()
    {
        //--- Check Actions ---//
        if(($sAction = bx_get('mod_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_mod_err_cannot_process_action'));
            switch($sAction) {
                case 'activate':
                    $sValue = bx_process_input(bx_get('mod_value'));
                    if(empty($sValue))
                        break;

                    $aResult = $this->activate($sValue);
                    break;
            }

            echo json_encode($aResult);
            exit;
        }

        $this->aModule = $this->oDb->getModuleByName($this->sModule);
        if(empty($this->aModule) || !is_array($this->aModule))
            BxDolStudioTemplate::getInstance()->displayPageNotFound();

		$this->sPageRssHelpUrl = $this->aModule['help_url'];
		$this->sPageRssHelpId = $this->aModule['name'];

		$this->addMarkers(array(
			'module_name' => $this->aModule['name'],
			'module_uri' => $this->aModule['uri'],
			'module_title' => $this->aModule['title'],
		));

        $this->addAction(array(
            'type' => 'switcher',
            'name' => 'activate',
            'caption' => '_adm_txt_pca_active',
            'checked' => (int)$this->aModule['enabled'] == 1,
            'onchange' => "javascript:" . $this->getPageJsObject() . ".activate('" . $this->sModule . "', this)"
        ), false);
    }

    public function activate($sModule)
    {
        $aModule = $this->oDb->getModuleByName($sModule);
        if(empty($aModule) || !is_array($aModule))
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        bx_import('BxDolStudioInstallerUtils');
        $aResult = BxDolStudioInstallerUtils::getInstance()->perform($aModule['path'], ((int)$aModule['enabled'] == 0 ? 'enable' : 'disable'), array('html_response' => true));
        if($aResult['code'] != 0)
            return $aResult;

        bx_import('BxDolStudioTemplate');
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
        if((int)$aModule['enabled'] == 0) {
            $aResult['content'] = $oTemplate->parseHtmlByName('page_content_2_col.html', array(
                'page_menu_code' => $this->getPageMenu(),
                'page_main_code' => $this->getPageCode()
            ));
        } else
            $aResult['content'] = "";

        return $aResult;
    }
}

/** @} */
