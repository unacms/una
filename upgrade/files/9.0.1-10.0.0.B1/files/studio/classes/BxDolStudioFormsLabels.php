<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioFormsLabels extends BxTemplStudioGrid
{
    protected $_oLabel;

    protected $_iParent;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioFormsQuery();

        $this->_oLabel = BxDolLabel::getInstance();

        $this->_iParent = bx_get('parent') !== false ? (int)bx_get('parent') : 0;
        $this->_aQueryAppend['parent'] = $this->_iParent;

        $this->_aParent = array();
        if(!empty($this->_iParent))
            $this->_aParent = $this->_oLabel->getLabels(array('type' => 'id', 'id' => $this->_iParent));
    }

    protected function _delete ($mixedId)
    {
        $this->_oLabel->onDelete($mixedId);

        return parent::_delete($mixedId);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `parent`=?", $this->_iParent);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
