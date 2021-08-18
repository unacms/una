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

        $this->$sMethod($oAlert);
    }

    protected function _processBxPersonsUnit($oAlert)
    {
        return $this->_processProfileUnit($oAlert);
    }

    protected function _processBxOrganizationsUnit($oAlert)
    {
        return $this->_processProfileUnit($oAlert);
    }

    protected function _processProfileUnit($oAlert)
    {
        $sModule = $oAlert->sUnit;
        $oModule = BxDolModule::getInstance($sModule);
        if(!$oModule)
            return;

        $sClassSize = $this->_oModule->_oConfig->getThumbSize(isset($oAlert->aExtras['template'][1]) ? $oAlert->aExtras['template'][1] : '');

        $aTmplVarsThumbnail = array(
            'class_size' => $sClassSize,
            'bx_if:show_thumb_image' => array(
                'content' => array(
                    'class_size' => $sClassSize
                )
            ),
            'bx_if:show_thumb_letter' => array(
                'content' => array(
                    'class_size' => $sClassSize
                )
            )
        );

        $aTmplVars = array_merge(array(
            'bx_if:show_thumbnail' => array(
                'content' => $aTmplVarsThumbnail
            )
        ), $aTmplVarsThumbnail);

        $oAlert->aExtras['tmpl_vars'] = array_merge_recursive($oAlert->aExtras['tmpl_vars'], $aTmplVars);
    }
}

/** @} */
