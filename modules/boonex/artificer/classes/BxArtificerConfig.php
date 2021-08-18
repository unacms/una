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
    protected $_sThumbSizeDefault;
    protected $_aThumbSizes;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aPrefixes = array(
            'option' => 'bx_artificer_'
        );

        $this->_sThumbSizeDefault = 'thumb';
        $this->_aThumbSizes = array(
            'icon' => 'h-8 w-8',
            'thumb' => 'h-10 w-10',
            'ava' => 'h-24 w-24',
            'ava-big' => 'h-48'
        );
    }

    public function getThumbSize($sName = '')
    {
        if(empty($sName) || !isset($this->_aThumbSizes[$sName]))
            $sName = $this->_sThumbSizeDefault;

        return $this->_aThumbSizes[$sName];
    }
}

/** @} */
