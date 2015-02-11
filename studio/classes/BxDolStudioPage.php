<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_PAGE_HOME', 'home');
define('BX_DOL_STUDIO_PAGE_JS_OBJECT', 'oBxDolStudioPage');

define('BX_DOL_STUDIO_MIT_CAPTION', 'caption');
define('BX_DOL_STUDIO_MIT_ITEM', 'item');

class BxDolStudioPage extends BxDol
{
    protected $oDb;

    protected $aPage;
    protected $bPageMultiple;
    protected $sPageSelected;

    protected $sPageRssHelpObject;
	protected $sPageRssHelpUrl;
    protected $iPageRssHelpLength;
	protected $sPageRssHelpId;

    protected $aActions;
    protected $aMarkers;

    function __construct($mixedPageName)
    {
        parent::__construct();

        $this->oDb = new BxDolStudioPageQuery();

        $this->aPage = array();
        $this->bPageMultiple = false;
        $this->sPageSelected = '';

		$this->sPageRssHelpObject = 'sys_studio_page_help';
        $this->sPageRssHelpUrl = 'http://feed.boonex.com/?section={page_name}';
        $this->iPageRssHelpLength = 5;

        $this->aActions = array();
        $this->aMarkers = array();

        if(is_string($mixedPageName)) {
            $this->oDb->getPages(array('type' => 'by_page_name_full', 'value' => $mixedPageName), $this->aPage, false);
            if(empty($this->aPage) || !is_array($this->aPage))
                return;

            $this->aPage['bookmarked'] = $this->oDb->isBookmarked($this->aPage);
        } 
        else if(is_array($mixedPageName)) {
            $aPages = array();
            $this->oDb->getPages(array('type' => 'by_page_names_full', 'value' => array_keys($mixedPageName)), $aPages, false);
            if(empty($aPages) || !is_array($aPages))
                return;

            $this->bPageMultiple = true;
            foreach($aPages as $aPage) {
                if((int)$mixedPageName[$aPage['name']] == 1)
                    $this->sPageSelected = $aPage['name'];

                $aPage['bookmarked'] = $this->oDb->isBookmarked($aPage);

                $this->aPage[$aPage['name']] = $aPage;
            }
        }

        if(!$this->bPageMultiple) {
        	$this->sPageRssHelpId = $this->aPage['name'];

            $this->addAction(array(
                'type' => 'switcher',
                'name' => 'bookmark',
                'caption' => '_adm_txt_pca_favorite',
                'checked' => $this->aPage['bookmarked'],
                'onchange' => "javascript:" . BX_DOL_STUDIO_PAGE_JS_OBJECT . ".bookmark('" . $this->aPage['name'] . "', this)"
            ));

            $this->aMarkers['page_name'] = $this->aPage['name'];
        }
    }

    public function getRssHelpUrl()
    {
    	return bx_replace_markers($this->sPageRssHelpUrl, $this->aMarkers);
    }

    public function addAction($aAction, $bOnRight = true)
    {
        if($bOnRight)
            $this->aActions[] = $aAction;
        else
            $this->aActions = array_merge(array($aAction), $this->aActions);
    }

    /**
     * Add replace markers.
     * @param $a array of markers as key => value
     * @return true on success or false on error
     */
    public function addMarkers ($a)
    {
        if(empty($a) || !is_array($a))
			return false;

        $this->aMarkers = array_merge($this->aMarkers, $a);
        return true;
    }

    public function removeActions()
    {
        $this->aActions = array();
    }

    public function bookmark()
    {
        $bResult = $this->oDb->bookmark($this->aPage);
        if(!$bResult)
            return array('code' => 1, 'message' => _t('_adm_err_operation_failed'));

        $oTemplate = BxDolStudioTemplate::getInstance();

        return array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
    }

    protected function getSystemName($sValue)
    {
        return BxDolStudioUtils::getSystemName($sValue);
    }

    protected function getClassName($sValue)
    {
        return BxDolStudioUtils::getClassName($sValue);
    }

    protected function getModuleTitle($sName)
    {
        return BxDolStudioUtils::getModuleTitle($sName);
    }

    protected function getModuleIcon($sName, $sType = 'menu', $bReturnAsUrl = true)
    {
        return BxDolStudioUtils::getModuleIcon($sName, $sType, $bReturnAsUrl);
    }

    protected function getModules($bShowCustom = true, $bShowSystem = true)
    {
        return BxDolStudioUtils::getModules($bShowCustom, $bShowSystem);
    }
}

/** @} */
