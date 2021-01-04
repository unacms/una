<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
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
    protected $sPageUrl;
    protected $sPageSelected;

    protected $sPageRssHelpObject;
    protected $sPageRssHelpUrl;
    protected $iPageRssHelpLength;
    protected $sPageRssHelpId;

    protected $_sTypesPreList;

    protected $aMarkers;

    protected $sError;

    function __construct($mixedPageName)
    {
        parent::__construct();

        $this->oDb = BxDolStudioPageQuery::getInstance();

        $this->aPage = array();
        $this->bPageMultiple = false;
        $this->sPageSelected = '';       

        $this->sPageRssHelpObject = 'sys_studio_page_help';
        $this->sPageRssHelpUrl = 'http://feed.una.io/?section={page_name}';
        $this->iPageRssHelpLength = 5;

        $this->_sTypesPreList = 'sys_studio_widget_types';

        $this->aMarkers = array(
            'url_root' => BX_DOL_URL_ROOT,
            'url_studio' => BX_DOL_URL_STUDIO
        );

        $this->sError = false;

        if(is_string($mixedPageName)) {
            $this->aPage = $this->oDb->getPages(array('type' => 'by_page_name_full', 'value' => $mixedPageName));
            if(empty($this->aPage) || !is_array($this->aPage))
                return;
        } 
        else if(is_array($mixedPageName)) {
            $aPages = $this->oDb->getPages(array('type' => 'by_page_names_full', 'value' => array_keys($mixedPageName)));
            if(empty($aPages) || !is_array($aPages))
                return;

            $this->bPageMultiple = true;
            foreach($aPages as $aPage) {
                if((int)$mixedPageName[$aPage['name']] == 1)
                    $this->sPageSelected = $aPage['name'];

                $this->aPage[$aPage['name']] = $aPage;
            }
        }
    }

    public function getPageUrl()
    {
        if(empty($this->sPageUrl) && !empty($this->aPage['wid_url']))
            $this->sPageUrl = $this->aPage['wid_url'];

        return bx_replace_markers($this->sPageUrl, $this->aMarkers);
    }

    public function getPageTypeUrl()
    {
        $sUrl = BxTemplStudioLauncher::getInstance()->getPageUrl();
        if(empty($this->aPage['wid_type']))
            return $sUrl;

        return bx_append_url_params($sUrl, array(
            'type' => $this->aPage['wid_type']
        ));
    }

    public function getRssHelpUrl()
    {
    	return bx_replace_markers($this->sPageRssHelpUrl, $this->aMarkers);
    }

    public function getPageTypes($bFullInfo = true)    
    {
        return BxDolFormQuery::getDataItems($this->_sTypesPreList, false, $bFullInfo ? BX_DATA_VALUES_ALL : BX_DATA_VALUES_DEFAULT);
    }

    public function getPageTypeIcon()
    {
        if(empty($this->aPage['wid_type']))
            return false;

        $sType = $this->aPage['wid_type'];
        $aTypes = $this->getPageTypes();
        if(empty($aTypes[$sType]) || empty($aTypes[$sType]['Data']))
            return false;

        $aTypeData = unserialize($aTypes[$sType]['Data']);
        if(empty($aTypeData['icon']))
            return false;

        return $aTypeData['icon'];
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

    public function setError($sError)
    {
        $this->sError = $sError;
    }

    public function getError($bToDisplay = true)
    {
        return $bToDisplay ? MsgBox(_t($this->sError)) : $this->sError;
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

    protected function updateHistory()
    {
        if(empty($this->aPage['wid_id'])) 
            return;

        BxTemplStudioMenuTop::historyAdd($this->aPage);
    }
}

/** @} */
