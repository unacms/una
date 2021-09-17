<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolModuleTemplate extends BxDolTemplate
{
    protected $_oDb;
    protected $_oConfig;
    protected $_bObStarted = 0;
    protected $_oModule;

    /*
     * Constructor.
     */
    public function __construct(&$oConfig, &$oDb, $sRootPath = BX_DIRECTORY_PATH_ROOT, $sRootUrl = BX_DOL_URL_ROOT)
    {
        parent::__construct($sRootPath, $sRootUrl);

        $this->_oDb = &$oDb;
        $this->_oConfig = &$oConfig;

        $sName = $oConfig->getName();
        $sHomePath = $oConfig->getHomePath();
        $sHomeUrl = $oConfig->getHomeUrl();

        if(method_exists($this, 'addLocationBase'))
            $this->addLocationBase();
        $this->addLocation($sName, $sHomePath, $sHomeUrl);
        $this->addLocationJs($sName, $sHomePath . 'js/', $sHomeUrl . 'js/');
    }

    public function getModule()
    {
        if (!$this->_oModule) {
            $sName = $this->_oConfig->getName();
            $this->_oModule = BxDolModule::getInstance($sName);
        }
        return $this->_oModule;
    }
    
    /**
     * Initialize module template engine.
     * Note. The method is executed with the system, you shouldn't execute it in your subclasses.
     */
    public function init()
    {
        $this->loadTemplates();

        bx_import('BxTemplFunctions');
        $this->_oTemplateFunctions = BxTemplFunctions::getInstance($this);
    }

    public function addLocationBase() {}

    function addCssSystem($mixedFiles, $bDynamic = false)
    {
        $sResult = '';
        $bResult = false;

        foreach($this->getLocations() as $sKey => $aLocation) {
            $mixedResult = $this->_addFiles(BxDolTemplate::getInstance(), 'addCssSystem', 'isLocation', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, true, [$sKey => $aLocation]);
            if($bDynamic)
                $sResult .= $mixedResult;
            else
                $bResult |= $mixedResult;
        }

        return $bDynamic ? $sResult : $bResult;
    }

    function addCss($mixedFiles, $bDynamic = false)
    {
        $sResult = '';
        $bResult = false;

        foreach($this->getLocations() as $sKey => $aLocation) {
            $mixedResult = $this->_addFiles(BxDolTemplate::getInstance(), 'addCss', 'isLocation', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, true, [$sKey => $aLocation]);
            if($bDynamic)
                $sResult .= $mixedResult;
            else
                $bResult |= $mixedResult;
        }

        return $bDynamic ? $sResult : $bResult;
    }

    function addJsSystem($mixedFiles, $bDynamic = false)
    {
        return $this->_addFiles(BxDolTemplate::getInstance(), 'addJsSystem', 'isLocationJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, true);
    }

    function addJs($mixedFiles, $bDynamic = false)
    {
        return $this->_addFiles(BxDolTemplate::getInstance(), 'addJs', 'isLocationJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, true);
    }

    function addJsTranslation($mixedKey, $bDynamic = false)
    {
        return BxDolTemplate::getInstance()->addJsTranslation($mixedKey, $bDynamic);
    }

    function addStudioCss($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addCss', 'isLocation', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function addStudioCssSystem($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addCssSystem', 'isLocation', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function addStudioJs($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addJs', 'isLocationJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function addStudioJsSystem($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addJsSystem', 'isLocationJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function addStudioJsTranslation($mixedKey)
    {
        BxDolStudioTemplate::getInstance()->addJsTranslation($mixedKey);
    }

    function _addFiles($oTemplate, $sFuncAddFiles, $sFuncIsLocation, $sFuncAddLocation, $sFuncRemoveLocation, $sPath, $mixedFiles, $bDynamic = false, $bSearchInModule = true, $aLocations = array())
    {
        if($bSearchInModule) {
            if(empty($aLocations))
                $aLocations = [
                    $this->_oConfig->getName() => [
                        'path' => $this->_oConfig->getHomePath() . $sPath,
                        'url' => $this->_oConfig->getHomeUrl() . $sPath
                    ]
                ];

            foreach($aLocations as $sLocationKey => $aLocation) {
                if($oTemplate->$sFuncIsLocation($sLocationKey))
                    continue;

                $oTemplate->$sFuncAddLocation($sLocationKey, $aLocation['path'], $aLocation['url']);
                
                $aLocations[$sLocationKey]['delete'] = true;
            }
        }

        $mixedResult = $oTemplate->$sFuncAddFiles($mixedFiles, $bDynamic);

        if($bSearchInModule && !empty($aLocations))
            foreach($aLocations as $sLocationKey => $aLocation) 
                if(isset($aLocation['delete']) && $aLocation['delete'] === true)
                    $oTemplate->$sFuncRemoveLocation($sLocationKey);           

        return $mixedResult;
    }

    function addStudioInjection($sKey, $sType, $sData, $iReplace = 0)
    {
        BxDolStudioTemplate::getInstance()->aPage['injections']['page_0'][$sKey][] = array(
            'page_index' => 0,
            'key' => $sKey,
            'type' => $sType,
            'data' => $sData,
            'replace' => $iReplace
        );
    }

    function pageStart ()
    {
        if (0 == $this->_bObStarted)  {
            ob_start ();
            $this->_bObStarted = 1;
        }
    }

    function pageEnd ($isGetContent = true)
    {
        if (1 == $this->_bObStarted)  {
            $sRet = '';
            if ($isGetContent)
                $sRet = ob_get_clean();
            else
                ob_end_clean();
            $this->_bObStarted = 0;
            return $sRet;
        }
    }

    public function addCssJs()
    {
    }

    public function isMethodExists($s)
    {
        return method_exists($this->_oProxifiedObject, $s);
    }    
}

/** @} */
