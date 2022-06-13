<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseTemplate Base classes for template modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolDesigns');
bx_import('BxBaseModGeneralConfig');

class BxBaseModTemplateConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_iLogo;
    protected $_sLogoAlt;
    protected $_iLogoWidth;
    protected $_iLogoHeight;
    protected $_fLogoAspectRatio;
    protected $_fLogoAspectRatioDefault;

    protected $_sKeyLogoAspectRatio;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_iLogoWidth = 0;
        $this->_iLogoHeight = 0;
        $this->_fLogoAspectRatioDefault = BxDolDesigns::$fLogoAspectRatioDefault;
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;
        $sPrefix = $this->getPrefix('option');

        $this->_sKeyLogoAspectRatio = $sPrefix . 'site_logo_aspect_ratio';

        $this->_iLogo = (int)$this->_oDb->getParam($sPrefix . 'site_logo');
        $this->_sLogoAlt = $this->_oDb->getParam($sPrefix . 'site_logo_alt');
        $this->_fLogoAspectRatio = (float)$this->_oDb->getParam($this->_sKeyLogoAspectRatio);
    }

    public function getLogoParams()
    {
    	$sPrefix = $this->getPrefix('option');

    	return [
            $sPrefix . 'site_logo',
            $sPrefix . 'site_logo_alt'
    	];
    }

    public function getLogoValues($sUrl, $aInfo)
    {
        if(empty($this->_iLogoHeight))
            $this->_iLogoHeight = $this->_calculateLogoValuesHeight();

        if(!$this->_fLogoAspectRatio)
            $this->_fLogoAspectRatio = $this->_calculateLogoValuesAspectRatio($sUrl, $aInfo);

        $this->_iLogoWidth = (int)ceil($this->_iLogoHeight * $this->_fLogoAspectRatio);

        return [
            $this->_iLogoWidth,
            $this->_iLogoHeight,
            $this->_fLogoAspectRatio
        ];
    }

    public function getLogo()
    {
    	return $this->_iLogo;
    }

    public function getLogoAlt()
    {
    	return $this->_sLogoAlt;
    }

    public function getLogoWidth()
    {
    	return $this->_iLogoWidth;
    }

    public function getLogoHeight()
    {
    	return $this->_iLogoHeight;
    }
    
    protected function _calculateLogoValuesHeight()
    {
        $sPrefix = $this->getPrefix('option');

        $sHeaderHeight = $this->_oDb->getParam($sPrefix . 'header_height');
        if(!$sHeaderHeight)
            return 0;

        $iHeaderHeight = $this->_str2px($sHeaderHeight);
        if(!$iHeaderHeight)
            return 0;
        
        $sHeaderPaddings = $this->_oDb->getParam($sPrefix . 'header_content_padding');
        if(!$sHeaderPaddings)
            return $iHeaderHeight;

        $aHeaderPaddings = explode(' ', $sHeaderPaddings);
        if(empty($aHeaderPaddings) || !is_array($aHeaderPaddings))
            return $iHeaderHeight;

        switch(count($aHeaderPaddings)) {
            case 1;
            case 2;
                $iPTop = $iPBottom = $this->_str2px($aHeaderPaddings[0]);
                break;

            case 3;
            case 4;
                $iPTop = $this->_str2px($aHeaderPaddings[0]);
                $iPBottom = $this->_str2px($aHeaderPaddings[2]);
                break;
        }

        return $iHeaderHeight - $iPTop - $iPBottom;
    }
    
    protected function _calculateLogoValuesAspectRatio($sUrl, $aInfo)
    {
        if(!$sUrl)
            return $this->_fLogoAspectRatioDefault;

        $iWidth = $iHeight = 0;
        if(strpos($sUrl, '.svg') !== false)
            list($iWidth, $iHeight) = bx_get_svg_image_size($sUrl);
        else if(isset($aInfo['mime_type']) && strncmp($aInfo['mime_type'], 'image/', 6) === 0)
            list($iWidth, $iHeight) = getimagesize($sUrl);

        if(!$iHeight) 
            return $this->_fLogoAspectRatioDefault;

        $fResult = $iWidth / $iHeight;
        $this->_oDb->setParam($this->_sKeyLogoAspectRatio, $fResult);

        return $fResult;
    }

    protected function _str2px($sValue)
    {
        if(!$sValue)
            return 0;

        if(($iPosition = strpos($sValue, 'px')) !== false)
            return (int)substr($sValue, 0, $iPosition);

        if(($iPosition = strpos($sValue, 'rem')) !== false)
            return (int)ceil(16 * (float)substr($sValue, 0, $iPosition));

        return (int)$sValue;
    }
}

/** @} */
