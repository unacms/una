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

class BxAdsSourcesDetailsFormCheckerHelper extends BxDolFormCheckerHelper
{
    function checkHttps ($s)
    {
        return empty($s) || substr(BX_DOL_URL_ROOT, 0, 5) == 'https';
    }
}

class BxAdsFormSourcesDetails extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    protected $_bCollapseFirst;
    
    protected $_iProfileId;

    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sModule = 'bx_ads';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_bCollapseFirst = false;
        
        $this->_iProfileId = $this->_oModule->getProfileId();

        $this->init();
    }

    public function setProfileId($iProfileId = 0)
    {
        if(!empty($iProfileId))
            $this->_iProfileId = $iProfileId;
    }

    public function init()
    {
        $aInputs = $this->_oModule->_oDb->getSourcesDetailsForm();
        if(empty($aInputs))
            return false;

        $bCollapsed = $this->_bCollapseFirst;
        $iSource = 0;
        $sSource = "";
        $oSource = null;
        foreach($aInputs as $aInput) {
            if($iSource != $aInput['source_id']) {
                $this->aInputs['source_' . $aInput['source_id'] . '_begin'] = [
                    'type' => 'block_header',
                    'caption' => _t($aInput['source_caption']),
                    'info' => _t($aInput['source_description']),
                    'collapsable' => true,
                    'collapsed' => $bCollapsed
                ];

                $iSource = $aInput['source_id'];
                $sSource = $aInput['source_name'];
                $oSource = $this->_oModule->getObjectSource($sSource, $this->_iProfileId);
                $bCollapsed = true;
            }

            $this->aInputs[$aInput['name']] = [
                'type' => $aInput['type'],
                'name' => $aInput['name'],
                'caption' => _t($aInput['caption']),
                'value' => $oSource->getOption($aInput['name']),
                'info' => _t($aInput['description']),
            	'attrs' => [
                    'bx-data-source' => $iSource
            	],
                'checker' => [
                    'func' => $aInput['check_type'],
                    'params' => $aInput['check_params'],
                    'error' => _t($aInput['check_error']),
                ]
            ];

            //--- Make some field dependent actions ---//
            switch($aInput['type']) {
                case 'select':
                    if(empty($aInput['extra']))
                       break;

                    $aAddon = ['values' => []];

                    if(BxDolService::isSerializedService($aInput['extra']))
                        $aAddon['values'] = BxDolService::callSerialized($aInput['extra']);
                    else {
                        $aPairs = explode(',', $aInput['extra']);
                        foreach($aPairs as $sPair) {
                            $aPair = explode('|', $sPair);
                            $aAddon['values'][] = ['key' => $aPair[0], 'value' => _t($aPair[1])];
                        }
                    }
                    break;

                case 'checkbox':
                   $this->aInputs[$aInput['name']]['value'] = '1';
                    $aAddon = array('checked' => $oSource->getOption($aInput['name']) == '1');
                    break;

                case 'value':
                   $sName = str_replace($aInput['source_option_prefix'], '', $aInput['name']);
                   if(!in_array($sName, array('return_data_url', 'notify_url')))
                        break;

                   $sMethod = 'get' . bx_gen_method_name($sName);
                   if(method_exists($oSource, $sMethod))
                        $this->aInputs[$aInput['name']]['value'] = $oSource->$sMethod($this->_iProfileId);
                   break;
            }

            if(!empty($aAddon) && is_array($aAddon))
                $this->aInputs[$aInput['name']] = array_merge($this->aInputs[$aInput['name']], $aAddon);
        }

        $this->aInputs['source_' . $iSource . '_end'] = [
            'type' => 'block_end'
        ];
        $this->aInputs['submit'] = [
            'type' => 'submit',
            'name' => 'submit',
            'value' => _t('_bx_ads_form_sources_details_input_do_submit'),
        ];

        return true;
    }
}

/** @} */
