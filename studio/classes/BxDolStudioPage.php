<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDol');
bx_import('BxDolStudioUtils');
bx_import('BxDolStudioPageQuery');

define('BX_DOL_STUDIO_PAGE_HOME', 'home');
define('BX_DOL_STUDIO_PAGE_JS_OBJECT', 'oBxDolStudioPage');

define('BX_DOL_STUDIO_MIT_CAPTION', 'caption');
define('BX_DOL_STUDIO_MIT_ITEM', 'item');

class BxDolStudioPage extends BxDol {
    protected $oDb;
    protected $aPage;
    protected $bPageMultiple;
    protected $sPageSelected;
    protected $aActions;

    function __construct($mixedPageName) {
        parent::__construct();

        $this->oDb = new BxDolStudioPageQuery();

        $this->aPage = array();
        $this->bPageMultiple = false;
        $this->sPageSelected = '';
        $this->aActions = array();

        if(is_string($mixedPageName)) {            
            $this->oDb->getPages(array('type' => 'by_page_name_full', 'value' => $mixedPageName), $this->aPage, false);
            if(empty($this->aPage) || !is_array($this->aPage))
                return;

            $this->aPage['bookmarked'] = $this->oDb->isBookmarked($this->aPage);
        }
        else if(is_array($mixedPageName))  {
            $aPages = array();
            $this->oDb->getPages(array('type' => 'by_page_names_full', 'value' => array_keys($mixedPageName)), $aPages, false);
            if(empty($this->aPage) || !is_array($this->aPage))
                return;

            $this->bPageMultiple = true;
            foreach($aPages as $aPage) {
                if((int)$mixedPageName[$aPage['name']] == 1)
                    $this->sPageSelected = $aPage['name'];

                $aPage['bookmarked'] = $this->oDb->isBookmarked($aPage);

                $this->aPage[$aPage['name']] = $aPage;
            }
        }

        if(!$this->bPageMultiple)
            $this->addAction(array(
                'type' => 'switcher',
            	'name' => 'bookmark',
            	'caption' => '_adm_txt_pca_favorite',
                'checked' => $this->aPage['bookmarked'],
                'onchange' => "javascript:" . BX_DOL_STUDIO_PAGE_JS_OBJECT . ".bookmark('" . $this->aPage['name'] . "', this)"
            ));
    }

    function addAction($aAction, $bOnRight = true) {
        if($bOnRight)
            $this->aActions[] = $aAction;
        else 
            $this->aActions = array_merge(array($aAction), $this->aActions);
    }

    function removeActions() {
        $this->aActions = array();
    }

    function bookmark() {
        $bResult = $this->oDb->bookmark($this->aPage);
        if(!$bResult)
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        bx_import('BxDolStudioTemplate');
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
        if($this->aPage['bookmarked']) {
            $aResult['icon'] = $oTemplate->getIconUrl('ics-star-act.png');
            $aResult['title'] = bx_js_string(_t('_adm_txt_favorites_remove'));
        }
        else {
            $aResult['icon'] = $oTemplate->getIconUrl('ics-star-pas.png');
            $aResult['title'] = bx_js_string(_t('_adm_txt_favorites_add'));
        }

        return $aResult;
    }

    protected function getSystemName($sValue) {
        return BxDolStudioUtils::getSystemName($sValue);
    }

    protected function getClassName($sValue) {
        return BxDolStudioUtils::getClassName($sValue);
    }

    protected function getModuleTitle($sName) {
        return BxDolStudioUtils::getModuleTitle($sName);
    }

    protected function getModuleIcon($sName, $sType = 'menu') {
        return BxDolStudioUtils::getModuleIcon($sName, $sType);
    }
    
    protected function getModules($bShowCustom = true, $bShowSystem = true) {
        return BxDolStudioUtils::getModules($bShowCustom, $bShowSystem);
    }
}

/** @} */
