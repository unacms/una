<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModTemplateConfig');

class BxArtificerConfig extends BxBaseModTemplateConfig
{
    protected $_aReplacements;

    protected $_sThumbSizeDefault;
    protected $_aThumbSizes;
    protected $_aThumbSizeByTemplate;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aJsClasses = array_merge($this->_aJsClasses, array(
            'utils' => 'BxArtificerUtils'
        ));

        $this->_aJsObjects = array_merge($this->_aJsObjects, array(
            'utils' => 'oBxArtificerUtils'
        ));

        $this->_iLogoHeight = 40;
        $this->_iMarkHeight = 40;

        $this->_aPrefixes = [
            'option' => 'bx_artificer_'
        ];

        $this->_aReplacements = [
            'bx-def-margin-sec-neg' => '-m-2',
        ];
                
        $this->_sThumbSizeDefault = 'thumb';
        $this->_aThumbSizes = [
            'icon' => 'h-8 w-8',
            'thumb' => 'h-10 w-10',
            'ava' => 'h-24 w-24',
            'ava-big' => 'w-48 h-48'
        ];
        $this->_aThumbSizeByTemplate = [
            'unit_with_cover.html' => 'h-24 w-24' //--- 'ava' size
        ];
    }

    public function getColorScheme()
    {
        return getParam('bx_artificer_color_scheme');
    }

    public function getLogoParams()
    {
    	$sPrefix = $this->getPrefix('option');

    	return [
            $sPrefix . 'site_logo',
            $sPrefix . 'site_mark',
            $sPrefix . 'site_logo_alt'
    	];
    }

    public function getReplacements()
    {
        return $this->_aReplacements;
    }

    public function getThumbSize($sName = '', $sTemplate = '')
    {
        if (empty($sName))
            $sName = 'thumb';
        
        if(!empty($sName) && isset($this->_aThumbSizes[$sName]))
            return $this->_aThumbSizes[$sName];

        if(!empty($sTemplate) && isset($this->_aThumbSizeByTemplate[$sTemplate]))
            return $this->_aThumbSizeByTemplate[$sTemplate];

        return $this->_sThumbSizeDefault;
    }
}

/** @} */
