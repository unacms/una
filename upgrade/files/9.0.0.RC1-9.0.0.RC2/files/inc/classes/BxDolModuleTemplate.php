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

        $this->addLocation($sName, $sHomePath, $sHomeUrl);
        $this->addLocationJs($sName, $sHomePath . 'js/', $sHomeUrl . 'js/');
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

    function addCss($mixedFiles, $bDynamic = false)
    {
        return $this->_addFiles(BxDolTemplate::getInstance(), 'addCss', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, true);
    }

    function addJs($mixedFiles, $bDynamic = false)
    {
        return $this->_addFiles(BxDolTemplate::getInstance(), 'addJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, true);
    }

    function addJsTranslation($mixedKey)
    {
        BxDolTemplate::getInstance()->addJsTranslation($mixedKey);
    }

    function addStudioCss($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addCss', 'addLocation', 'removeLocation', '', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function addStudioJs($mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        return $this->_addFiles(BxDolStudioTemplate::getInstance(), 'addJs', 'addLocationJs', 'removeLocationJs', 'js/', $mixedFiles, $bDynamic, $bSearchInModule);
    }

    function _addFiles($oTemplate, $sFuncAddFiles, $sFuncAddLocation, $sFuncRemoveLocation, $sPath, $mixedFiles, $bDynamic = false, $bSearchInModule = true)
    {
        $sLocationKey = '';
        $bLocationRemove = false;

        if($bSearchInModule) {
            if(strpos($sFuncAddLocation, 'Dynamic') === false) {
                $sLocationKey = $this->_oConfig->getName();
                if(!$oTemplate->isLocation($sLocationKey)) {
                    $sLocationKey = $oTemplate->$sFuncAddLocation($sLocationKey, $this->_oConfig->getHomePath() . $sPath, $this->_oConfig->getHomeUrl() . $sPath);
                    $bLocationRemove = true;
                }
            }
            else {
                $sLocationKey = $oTemplate->$sFuncAddLocation($this->_oConfig->getHomePath() . $sPath, $this->_oConfig->getHomeUrl() . $sPath);
                $bLocationRemove = true;
            }
        }

        $mixedResult = $oTemplate->$sFuncAddFiles($mixedFiles, $bDynamic);

        if($bLocationRemove && $sLocationKey != '')
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
}

/** @} */
