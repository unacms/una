<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolGridRelations extends BxDolGridConnectionOut
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sConnectionObject = 'sys_profiles_relations';
    }

    protected function _getCellRelation ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oConnection->getRelationTranslation($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellMutual($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = _t('_sys_' . ((int)$mixedValue != 1 ? 'un' : '') . 'confirmed');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getDataSql ($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!$this->_bOwner)
            $this->_aOptions['source'] .= " AND `c`.`mutual`='1'";

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
