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

class BxElsSearch extends BxTemplSearch
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aChoice = '')
    {
        parent::__construct($aChoice);

        $this->aClasses = array();
        $this->aChoice = array();

        $this->_sModule = 'bx_elasticsearch';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $aSystems = BxDolContentInfo::getSystems();
        foreach($aSystems as $aSystem) 
            $this->aClasses[$aSystem['name']] = array_merge($aSystem, array(
                'GlobalSearch' => 1
            ));

        if (is_array($aChoice) && !empty($aChoice))
            foreach ($aChoice as $sValue) {
                if (!isset($this->aClasses[$sValue]))
                    continue;

                $this->aChoice[$sValue] = $this->aClasses[$sValue];
            }
        else
            $this->aChoice = $this->aClasses;
    }

    public function response ()
    {
        $sKeyword = bx_process_input(bx_get('keyword'));

        $sUnitTemplate = '';
        if($this->_bLiveSearch)
            $sUnitTemplate = 'unit_live_search.html';

        $sResult = '';
        foreach ($this->aChoice as $sKey => $aValue) {
            if (!$this->_sMetaType && !$aValue['GlobalSearch'])
                continue;

            $aResults = $this->_oModule->serviceSearch($sKeyword, $sKey);
            if((int)$aResults['total'] == 0 || empty($aResults['hits']))
                continue;

            $sContent = '';
            foreach ($aResults['hits'] as $aResult)
                $sContent .= BxDolContentInfo::getObjectInstance($aResult['_type'])->getContentSearchResultUnit($aResult['_id'], $sUnitTemplate);

            $sResult .= $this->_oModule->_oTemplate->parseHtmlByName('search_results_block.html', array(
                'id' => $aValue['id'],
                'content' => DesignBoxContent(_t($aValue['title']), $sContent, BX_DB_PADDING_DEF)
            ));
        }

        return $sResult;
    }
}

/** @} */
