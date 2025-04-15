<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAssistants extends BxTemplStudioGrid
{
    protected $_oDb;

    protected $_iProfileIdAi;

    protected $_bShowHidden;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oDb = new BxDolStudioAgentsQuery();

        $this->_iProfileIdAi = BxDolAI::getInstance()->getProfileId();

        $this->_bShowHidden = false;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(!$this->_bShowHidden)
            $this->_aOptions['source'] .= " AND `hidden`=0 ";

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
