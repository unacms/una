<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxDolStudioInstallerUtils');

define('BX_DOL_STUDIO_STR_TYPE_DEFAULT', 'downloaded');

class BxDolStudioStore extends BxTemplStudioPage
{
    protected $sPage;
    protected $aContent;

    protected $iClient;
    protected $sClientKey; 

    protected $sStoreDataUrlPublic;
    protected $bAuthAccessUpdates;

    function __construct($sPage = "")
    {
        parent::__construct('store');

        $this->oDb = new BxDolStudioStoreQuery();

        $this->sStoreDataUrlPublic = BxDolStudioInstallerUtils::getInstance()->getStoreDataUrl();
        $this->bAuthAccessUpdates = false;

        $this->sPage = BX_DOL_STUDIO_STR_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;

        $this->iClient = BxDolStudioOAuth::getAuthorizedClient();
        if(!empty($this->iClient))
        	$this->sClientKey = $this->oDb->getParam('sys_oauth_key');

        //--- Check actions ---//
        if(($sAction = bx_get('str_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_mod_err_cannot_process_action'));
            switch($sAction) {
                case 'get-file':
                    $iFileId = (int)bx_get('str_id');
                    $aResult = $this->getFile($iFileId);
                    break;

                case 'get-product':
                    $sModuleName = bx_process_input(bx_get('str_id'));
                    $aResult = $this->getProduct($sModuleName);
                    break;

                case 'get-update':
                    $sModuleName = bx_process_input(bx_get('str_id'));
                    $aResult = $this->getUpdate($sModuleName);
                    break;

				case 'get-update-and-install':
                    $sModuleName = bx_process_input(bx_get('str_id'));
                    $aResult = $this->getUpdate($sModuleName, true);
                    break;

                case 'get-products-by-type':
                    $this->sPage = bx_process_input(bx_get('str_value'));

                    $sContent = $this->getPageCode();
                    if(!empty($sContent))
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'content' => $sContent);
                    else
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_act_err_failed_page_loading'));
                    break;

                case 'get-products-by-page':
                    $this->sPage = bx_process_input(bx_get('str_type'));

                    $sContent = $this->getPageContent();
                    if(!empty($sContent))
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'content' => $sContent);
                    else
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_act_err_failed_page_loading'));
                    break;

                case 'add-to-cart':
                    $iVendor = (int)bx_get('str_vendor');
                    $iItem = (int)bx_get('str_item');
                    $iItemCount = 1;

                    if(empty($iVendor) || empty($iItem)) {
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_err_modules_cannot_add_to_cart'));
                        break;
                    }

                    BxDolStudioCart::getInstance()->add($iVendor, $iItem, $iItemCount);
                    $aResult = array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => _t('_adm_msg_modules_success_added_to_cart'));
                    break;

                case 'delete-from-cart':
                    $iVendor = (int)bx_get('str_vendor');
                    $iItem = (int)bx_get('str_item');

                    if(empty($iVendor)) {
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_err_modules_cannot_delete_from_cart'));
                        break;
                    }

                    BxDolStudioCart::getInstance()->delete($iVendor, $iItem);
                    $aResult = array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => '');
                    break;

                case 'checkout-cart':
                    $iVendor = (int)bx_get('str_vendor');
                    if(empty($iVendor)) {
                        $aResult = array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_err_modules_cannot_checkout_empty_vendor'));
                        break;
                    }

                    $sLocation = $this->checkoutCart($iVendor);
                    $aResult = array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => '', 'redirect' => $sLocation);
                    break;

                case 'install':
                    $sValue = bx_process_input(bx_get('str_value'));
                    if(empty($sValue))
                        break;

                    $aResult = BxDolStudioInstallerUtils::getInstance()->perform($sValue, 'install', array('auto_enable' => true, 'html_response' => true));
                    break;

                case 'update':
                    $sValue = bx_process_input(bx_get('str_value'));
                    if(empty($sValue))
                        break;

                    $aResult = BxDolStudioInstallerUtils::getInstance()->perform($sValue, 'update', array('html_response' => true));
                    break;

                case 'delete':
                    $sValue = bx_process_input(bx_get('str_value'));
                    if(empty($sValue))
                        break;

                    $aResult = BxDolStudioInstallerUtils::getInstance()->perform($sValue, 'delete', array('html_response' => true));
                    break;
            }

            if(!empty($aResult['message']))
				$aResult['message'] = BxTemplStudioFunctions::getInstance()->inlineBox('', BxDolStudioTemplate::getInstance()->parseHtmlByName('mod_action_result_inline.html', array('content' => $aResult['message'])), true);

            echo json_encode($aResult);
            exit;
        }
    }

    protected function loadGoodies()
    {
        $iPerPage = 6;
        $aProducts = array();
        $sJsObject = $this->getPageJsObject();

        $oJson = BxDolStudioJson::getInstance();

        // Load featured
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_featured',
            'actions' => array(
                array('name' => 'featured', 'caption' => '_adm_action_cpt_see_all_featured', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('featured', this)")
            ),
            'items' => $oJson->load($this->sStoreDataUrlPublic . 'json_browse_featured', array('start' => 0, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey))
        );


        // Load modules
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_extensions',
            'actions' => array(
                array('name' => 'modules', 'caption' => '_adm_action_cpt_see_all_extensions', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('extensions', this)")
            ),
            'items' => $oJson->load($this->sStoreDataUrlPublic . 'json_browse_by_category', array('value' => 'extensions', 'start' => 0, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey))
        );

        // Load templates
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_templates',
            'actions' => array(
                array('name' => 'templates', 'caption' => '_adm_action_cpt_see_all_templates', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('templates', this)")
            ),
            'items' => $oJson->load($this->sStoreDataUrlPublic . 'json_browse_by_category', array('value' => 'templates', 'start' => 0, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey))
        );

        // Load languages
        $aProducts[] = array(
            'caption' => '_adm_block_cpt_last_translations',
            'actions' => array(
                array('name' => 'languages', 'caption' => '_adm_action_cpt_see_all_translations', 'url' => 'javascript:void(0)', 'onclick' => $sJsObject . ".changePage('translations', this)")
            ),
            'items' => $oJson->load($this->sStoreDataUrlPublic . 'json_browse_by_category', array('value' => 'translations', 'start' => 0, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey))
        );

        return $aProducts;
    }

    protected function loadFeatured($iStart, $iPerPage)
    {
        return BxDolStudioJson::getInstance()->load($this->sStoreDataUrlPublic . 'json_browse_featured', array('start' => $iStart, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey));
    }

	protected function loadCategory($sCategory, $iStart, $iPerPage)
    {
        return BxDolStudioJson::getInstance()->load($this->sStoreDataUrlPublic . 'json_browse_by_category', array('value' => $sCategory, 'start' => $iStart, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey));
    }

    protected function loadTag($sTag, $iStart, $iPerPage)
    {
        return BxDolStudioJson::getInstance()->load($this->sStoreDataUrlPublic . 'json_browse_by_tag', array('value' => $sTag, 'start' => $iStart, 'per_page' => $iPerPage, 'client' => $this->iClient, 'key' => $this->sClientKey));
    }

    protected function loadPurchases()
    {
        return BxDolStudioInstallerUtils::getInstance()->checkModules(true);
    }

    protected function loadUpdates()
    {
        return BxDolStudioInstallerUtils::getInstance()->checkUpdates($this->bAuthAccessUpdates);
    }

    protected function loadCheckout()
    {
        $oJson = BxDolStudioJson::getInstance();

        $aVendors = BxDolStudioCart::getInstance()->parseByVendor();

        $aResult = array();
        foreach($aVendors as $sVendor => $aItems) {
            $aIds = $aCounts = array();
            foreach($aItems as $aItem) {
                $aIds[] = $aItem['item_id'];
                $aCounts[$aItem['item_id']] = $aItem['item_count'];
            }

            $aProducts = $oJson->load($this->sStoreDataUrlPublic . 'json_browse_selected', array('products' => base64_encode(serialize($aIds))));
            if(!empty($aProducts))
                $aResult[$sVendor] = array(
                    'ids' => $aIds,
                    'counts' => $aCounts,
                    'products' => $aProducts
                );
        }

        return $aResult;
    }

    protected function loadDownloaded()
    {
        $oInstallerUtils = BxDolStudioInstallerUtils::getInstance();

        return array(
            'modules' => $oInstallerUtils->getModules(),
            'updates' => $oInstallerUtils->getUpdates()
        );
    }

    protected function loadProduct($sModuleName)
    {
        $oJson = BxDolStudioJson::getInstance();

        return $oJson->load($this->sStoreDataUrlPublic . 'json_get_product_by_name', array('value' => $sModuleName, 'client' => $this->iClient, 'key' => $this->sClientKey));
    }

    /*
     * Load package (module, update) using OAuth authorization.
     */
    protected function loadFile($iFileId)
    {
        return BxDolStudioInstallerUtils::getInstance()->downloadFileAuthorized($iFileId);
    }

    /*
     * Load update's package publicly.
     */
	protected function loadUpdate($sModuleName, $bAutoUpdate = false)
    {
        return BxDolStudioInstallerUtils::getInstance()->downloadUpdatePublic($sModuleName, $bAutoUpdate);
    }

    private function checkoutCart($iVendor)
    {
        $oCart = BxDolStudioCart::getInstance();

        $aItems = $oCart->getByVendor($iVendor);
        if(empty($aItems) || !is_array($aItems))
            return false;

        $aIds = array();
        foreach($aItems as $aItem)
            $aIds[] = $aItem['item_id'];

        $sSid = bx_site_hash();
        return $this->sStoreDataUrlPublic . 'purchase/' . $iVendor . '?sid=' . $sSid . '&products=' . base64_encode(implode(',', $aIds));
    }
}

/** @} */
