<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Search form
 */
class BxElsFormManage extends BxTemplFormView
{
	protected $_sModule;
	protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_elasticsearch';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs['index'])) {
            $this->aInputs['index']['value'] = $this->_oModule->_oConfig->getIndex();
        }

        if(isset($this->aInputs['type'])) {
            $aObjects = BxDolContentInfo::getSystems();
            ksort($aObjects);

            $aTypes = array(
                array('key' => '', 'value' => _t('_bx_elasticsearch_form_manage_input_type_value_all'))
            );
            foreach ($aObjects as $sObject => $aObject)
                $aTypes[] = array('key' => $sObject, 'value' => _t($aObject['title']));

            $this->aInputs['type']['values'] = $aTypes;
        }
    }

    public function getCode($bDynamicMode = false)
    {
        return $this->oTemplate->getJsCode('manage') . $this->oTemplate->parseHtmlByName('manage_form.html', array(
            'form_id' => $this->getId(),
            'form' => parent::getCode($bDynamicMode)
        ));
    }
}

/** @} */
