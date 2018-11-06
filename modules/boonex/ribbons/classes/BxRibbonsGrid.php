<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsGrid extends BxTemplGrid
{
    protected $MODULE;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_ribbons';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        parent::__construct ($aOptions, $oTemplate);
    }
    
    protected function _getCellText($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = strip_tags(htmlspecialchars_decode($mixedValue));
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);

        unset($a['attr']['bx_grid_action_independent']);
        $a['attr'] = array_merge($a['attr'], array(
            "onclick" => "window.open('" . $sUrl . "','_self');"
        ));

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getActionEdit ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(isset($aRow["date_sent"]) && $aRow["date_sent"] != '0')
            return '';

        $CNF = &$this->_oModule->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_EDIT_ENTRY'] . '&id=' . $aRow[$CNF['FIELD_ID']]);

        $a['attr'] = array_merge($a['attr'], array(
            "onclick" => "window.open('" . $sUrl . "','_self');"
        ));

        return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
