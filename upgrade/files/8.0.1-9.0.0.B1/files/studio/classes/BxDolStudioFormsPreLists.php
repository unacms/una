<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioFormsPreLists extends BxTemplStudioGrid
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioFormsQuery();
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $sModule = '';
        if(strpos($sFilter, $this->sParamsDivider) !== false)
            list($sModule, $sFilter) = explode($this->sParamsDivider, $sFilter);

        if($sModule != '')
            $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `module`=?", $sModule);

        $aResults = parent::_getDataSql($sFilter, !empty($sOrderField) ? $sOrderField : 'title', $sOrderDir, $iStart, $iPerPage);

        $aLists = array();
        $this->oDb->getLists(array('type' => 'pairs_list_values'), $aLists, false);
        foreach($aResults as $iKey => $aResult)
            if(isset($aLists[$aResult['key']]))
                $aResults[$iKey]['values_count'] = (int)$aLists[$aResult['key']];

        return $aResults;
    }
}

/** @} */
