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


class BxArtificerAlertsResponse extends BxBaseModTemplateAlertsResponse
{
    function __construct()
    {
        $this->_sModule = 'bx_artificer';

        parent::__construct();
    }

    protected function _processSystemChangeLogo($oAlert)
    {
        $sPrefix = $this->_oModule->_oConfig->getPrefix('option');

        if(!in_array($oAlert->aExtras['option'], ['sys_site_logo', $sPrefix . 'site_logo']))
            return;

        setParam($sPrefix . 'site_logo_aspect_ratio', '');
    }

    protected function _processSystemGetObject($oAlert)
    {
        if(!$this->_isActive())
            return;

        if(empty($oAlert->aExtras['type']))
            return;

        switch($oAlert->aExtras['type']) {
            case 'menu':
                if(!($oAlert->aExtras['object'] instanceof BxBaseModGeneralMenuViewActions))
                    break;

                $oAlert->aExtras['object']->setShowAsButton(false);
                break;
        }
    }

    protected function _processProfileUnit($oAlert)
    {
        if(!$this->_isActive())
            return;

        $sModule = $oAlert->aExtras['module'];
        $oModule = BxDolModule::getInstance($sModule);
        if(!$oModule)
            return;

        $sTemplate = !empty($oAlert->aExtras['template']) && is_array($oAlert->aExtras['template']) ? $oAlert->aExtras['template'][0] : $oAlert->aExtras['template'];
        $sClassSize = $this->_oModule->_oConfig->getThumbSize(isset($oAlert->aExtras['template'][1]) ? $oAlert->aExtras['template'][1] : '', $sTemplate);

        $aTmplVars['class_size'] = $sClassSize;
        $aTmplVars['bx_if:show_thumb_letter']['content']['class_size'] = $sClassSize;
        $aTmplVars['bx_if:show_thumb_image']['content']['class_size'] = $sClassSize;
        
        $aTmplVars['bx_if:show_thumbnail']['content']['class_size'] = $sClassSize;
        $aTmplVars['bx_if:show_thumbnail']['content']['bx_if:show_thumb_letter']['content']['class_size'] = $sClassSize;
        $aTmplVars['bx_if:show_thumbnail']['content']['bx_if:show_thumb_image']['content']['class_size'] = $sClassSize;
    }
}

/** @} */
