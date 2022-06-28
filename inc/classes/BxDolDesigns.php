<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolDesigns extends BxDolFactory implements iBxDolSingleton
{
    public static $fLogoAspectRatioDefault = 1.0;
    public static $fMarkAspectRatioDefault = 1.0;

    protected $sDesign;
    protected $oDesign;

    protected $aParams;
    protected $aValues;

    protected $sLogoStorage;
    protected $sLogoTranscoder;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->sLogoStorage = 'sys_images_custom';
        $this->sLogoTranscoder = 'sys_custom_images';
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolDesigns();
            $GLOBALS['bxDolClasses'][__CLASS__]->init();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    protected function init()
    {
    	$this->sDesign = BxDolTemplate::getInstance()->getCode();

    	$aDesign = BxDolModuleQuery::getInstance()->getModuleByUri($this->sDesign);
    	if(!empty($aDesign) && is_array($aDesign))
            $this->oDesign = BxDolModule::getInstance($aDesign['name']);

    	//--- Init site's logo params.
    	if($this->oDesign instanceof BxDolModule && method_exists($this->oDesign->_oConfig, 'getLogoParams')) {
            list(
                $this->aParams['logo'], 
                $this->aParams['mark'], 
                $this->aParams['logo_alt']
            ) = $this->oDesign->_oConfig->getLogoParams();

            list(
                $this->aValues['logo_width'], 
                $this->aValues['logo_height'],
                $this->aValues['logo_aspect_ratio'],

                $this->aValues['mark_width'],
                $this->aValues['mark_height'],
                $this->aValues['mark_aspect_ratio']
            ) = $this->oDesign->_oConfig->getLogoValues($this->getSiteLogoUrl(), $this->getSiteLogoInfo(), $this->getSiteMarkUrl(), $this->getSiteMarkInfo());
        }
    }

    public function getSiteLogo()
    {
    	return $this->getSiteLogoParam('logo');
    }

    public function getSiteLogoUrl($iFileId = 0, $bOriginal = true)
    {
        if(!$iFileId)
            $iFileId = (int)$this->getSiteLogo();
        if(!$iFileId) 
            return false;

        if($bOriginal)
            return BxDolStorage::getObjectInstance($this->sLogoStorage)->getFileUrlById($iFileId);

        $aParams = [];
        if(($iLogoWidth = (int)$this->getSiteLogoWidth()) > 0)
            $aParams['x'] = $iLogoWidth;

        if(($iLogoHeight = (int)$this->getSiteLogoHeight()) > 0)
            $aParams['y'] = $iLogoHeight;

        if(!empty($aParams))
            $sFileUrl = BX_DOL_URL_ROOT . bx_append_url_params('image_transcoder.php', array_merge(array('o' => $this->sLogoTranscoder, 'h' => $iFileId), $aParams));
        else 
            $sFileUrl = BxDolTranscoder::getObjectInstance($this->sLogoTranscoder)->getFileUrl($iFileId);

        return !empty($sFileUrl) ? $sFileUrl : false;
    }

    public function getSiteMark()
    {
    	return $this->getSiteLogoParam('mark');
    }

    public function getSiteMarkUrl($iFileId = 0, $bOriginal = true)
    {
        if(!$iFileId)
            $iFileId = (int)$this->getSiteMark();
        if(!$iFileId) 
            return false;
        
        return $this->getSiteLogoUrl($iFileId, $bOriginal);
    }

    public function getSiteLogoInfo($iFileId = 0)
    {
        if(!$iFileId)
            $iFileId = (int)$this->getSiteLogo();
        if(!$iFileId) 
            return false;

        return BxDolStorage::getObjectInstance($this->sLogoStorage)->getFile($iFileId);
    }

    public function getSiteMarkInfo($iFileId = 0)
    {
        if(!$iFileId)
            $iFileId = (int)$this->getSiteMark();
        if(!$iFileId) 
            return false;

        return BxDolStorage::getObjectInstance($this->sLogoStorage)->getFile($iFileId);
    }

    public function getSiteLogoAlt()
    {
    	return $this->getSiteLogoParam('logo_alt');
    }

    public function getSiteLogoWidth()
    {
    	return ($iResult = $this->getSiteLogoValue('logo_width')) !== false ? $iResult : 0;
    }

    public function getSiteLogoHeight()
    {
        return ($iResult = $this->getSiteLogoValue('logo_height')) !== false ? $iResult : 0;
    }

    public function getSiteMarkWidth()
    {
    	return ($iResult = $this->getSiteLogoValue('mark_width')) !== false ? $iResult : 0;
    }

    public function getSiteMarkHeight()
    {
        return ($iResult = $this->getSiteLogoValue('mark_height')) !== false ? $iResult : 0;
    }

    protected function getSiteLogoParam($sName, $bGetSystem = false)
    {
    	if(!empty($this->aParams[$sName]) && !$bGetSystem) {
            $sResult = getParam($this->aParams[$sName]);
            if(!empty($sResult))
                return $sResult;
    	}

    	return getParam('sys_site_' . $sName);
    }

    protected function getSiteLogoValue($sName)
    {
        return isset($this->aValues[$sName]) ? $this->aValues[$sName] : false;
    }
}

/** @} */
