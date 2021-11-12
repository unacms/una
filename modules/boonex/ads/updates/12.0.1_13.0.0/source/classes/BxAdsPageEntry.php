<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxAdsPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';

        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(($oInformer = BxDolInformer::getInstance($this->_oTemplate)) !== false) {
            $aInformers = array();
            $sStatus = isset($CNF['FIELD_STATUS']) && isset($this->_aContentInfo[$CNF['FIELD_STATUS']]) ? $this->_aContentInfo[$CNF['FIELD_STATUS']] : '';

            //--- Display 'auction' informer.
            if(!empty($CNF['INFORMERS']['auction']) && isset($CNF['INFORMERS']['auction']['map'][$sStatus])) {
                $aInformer = $CNF['INFORMERS']['auction'];
                $aInformers[] = array ('name' => $aInformer['name'], 'msg' => _t($aInformer['map'][$sStatus]['msg']), 'type' => $aInformer['map'][$sStatus]['type']);
            }

            //--- Add informers
            if($aInformers)
                foreach($aInformers as $aInformer)
                    $oInformer->add($aInformer['name'], $this->_replaceMarkers($aInformer['msg']), $aInformer['type']);
        }
    }

    public function getCode ()
    {
        $sResult = parent::getCode();
        if(!empty($sResult))
            $sResult .= $this->_oModule->_oTemplate->getJsCode('entry');

        $this->_oModule->_oTemplate->addCss(array('entry.css'));
        $this->_oModule->_oTemplate->addJs(array('entry.js'));
        return $sResult;
    }

    protected function _setSubmenu($aParams)
    {
        parent::_setSubmenu(array_merge($aParams, array(
            'title' => '',
            'icon' => ''
        )));
    }
}

/** @} */
