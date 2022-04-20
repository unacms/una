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


class BxArtificerAlertsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_artificer';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(!method_exists($this, $sMethod))
            return;

        if(BxDolTemplate::getInstance()->getCode() != $this->_oModule->_oConfig->getUri())
            return;

        $this->$sMethod($oAlert);
    }

    protected function _processSystemGetObject($oAlert)
    {
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
