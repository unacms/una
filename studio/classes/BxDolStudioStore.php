<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioStoreQuery');

define('BX_DOL_STUDIO_STR_TYPE_DEFAULT', 'goodies');

class BxDolStudioStore extends BxTemplStudioPage {
    protected $sPage;
    protected $aProducts;

    function BxDolStudioStore($sPage = "") {
        parent::BxTemplStudioPage('store');

        $this->oDb = new BxDolStudioStoreQuery();

        $this->sPage = BX_DOL_STUDIO_STR_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

        //--- Check actions ---//
        if(($sAction = bx_get('str_action')) !== false) {
	        $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_mod_err_cannot_process_action'));
	        switch($sAction) {
	            case 'get-products-by-type':
	                $sValue = bx_process_input(bx_get('str_value'));

	                $sMethod = 'load' . ucfirst($sValue);
	                if(method_exists($this, $sMethod)) {
	                    $this->sPage = $sValue;
		                $this->aProducts = $this->$sMethod();
                        $aResult = array('code' => 0, 'content' => $this->getPageCode());
	                }
	                else 
	                    $aResult = array('code' => 1, 'message' => _t('_adm_act_err_failed_page_loading'));
	                break;

	        	case 'install':
	        	    $sValue = bx_process_input(bx_get('str_value'));
	        	    if(empty($sValue))
	                    break;

                    bx_import('BxDolStudioInstallerUtils');
	        		$aResult = BxDolStudioInstallerUtils::getInstance()->perform($sValue, 'install');
	        		break;
	        }

	        if(!empty($aResult['message'])) {
                bx_import('BxDolStudioTemplate');
                $sContent = BxDolStudioTemplate::getInstance()->parseHtmlByName('mod_action_result.html', array('content' => $aResult['message']));

                bx_import('BxTemplStudioFunctions');
                $aResult['message'] = BxTemplStudioFunctions::getInstance()->transBox($sContent);
            }

	        $oJson = new Services_JSON();		        
            echo $oJson->encode($aResult);
            exit;
        }
    }

    protected function loadGoodies() {
        $aProducts = array();
        $sJsObject = $this->getPageJsObject();

        // Load featured
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_featured',
            'actions' => array(
                array('name' => 'featured', 'caption' => '_adm_action_cpt_see_all_featured', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('featured', this)")
            ), 
            'items' => array()
        );

        // Load modules
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_modules',
            'actions' => array(
                array('name' => 'modules', 'caption' => '_adm_action_cpt_see_all_modules', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('modules', this)")
            ), 
            'items' => array()
        );

        // Load templates
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_templates',
            'actions' => array(
                array('name' => 'templates', 'caption' => '_adm_action_cpt_see_all_templates', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('templates', this)")
            ), 
            'items' => array()
        );

        // Load languages
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_languages',
            'actions' => array(
                array('name' => 'languages', 'caption' => '_adm_action_cpt_see_all_languages', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('languages', this)")
            ), 
            'items' => array()
        );
                
        return $aProducts;
    }

    protected function loadPurchases() {
        $aProducts = array();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aInstalled = $this->oDb->getModules();
        $aInstalledInfo = array();
        foreach($aInstalled as $aModule)
            $aInstalledInfo[$aModule['path']] = $aModule;
        $aInstalledPathes = array_keys($aInstalledInfo);

        $iOrder = 0;
        $sPath = BX_DIRECTORY_PATH_ROOT . 'modules/';
        if(($rHandleVendor = opendir($sPath)) !== false) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor)) 
                    continue;

                if(($rHandleModule = opendir($sPath . $sVendor)) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

                        $sConfigPath = $sPath . $sVendor . '/' . $sModule . '/install/config.php';
                        if(!file_exists($sConfigPath)) 
                            continue;

                        include($sConfigPath);
                        $sModulePath = $aConfig['home_dir'];
                        $sTitle = bx_process_output($aConfig['title']);

                        $bInstalled = in_array($sModulePath, $aInstalledPathes);
                        $bEnabled = $bInstalled && (int)$aInstalledInfo[$sModulePath]['enabled'] == 1;

                        $sLinkMarket = '';
                        if(isset($aConfig['product_url'])) {
                            $aTmplVars = array(
                            	'vendor' => $aConfig['vendor'],
                            	'version' => $aConfig['version'],
                                'uri' => $aConfig['home_uri'],
                                'title' => $aConfig['title']
                            );

                            $sLinkMarket = $oTemplate->parseHtmlByContent(bx_html_attribute($aConfig['product_url']), $aTmplVars, array('{', '}'));
                        }

                        $aProducts[$sTitle] = array(
                        	'name' => $aConfig['name'],
                            'title' => $sTitle,
                        	'vendor' => $aConfig['vendor'],
                        	'version' => $aConfig['version'],
                            'uri' => $aConfig['home_uri'],
                            'dir' => $aConfig['home_dir'],
                            'title' => $sTitle,
                            'note' => isset($aConfig['note']) ? bx_process_output($aConfig['note']) : '',
                            'link_market' => $sLinkMarket,
                            'installed' => $bInstalled,
                            'enabled' => $bInstalled && $bEnabled
                        );
                    }
                    closedir($rHandleModule);
                }
            }
            closedir($rHandleVendor);
        }

        ksort($aProducts);
        return $aProducts;
    }
}
/** @} */