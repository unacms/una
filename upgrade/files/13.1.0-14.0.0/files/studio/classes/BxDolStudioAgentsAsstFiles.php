<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAsstFiles extends BxTemplStudioGrid
{
    protected $_oDb;

    protected $_iAssistantId;
    protected $_aAssistantInfo;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oDb = new BxDolStudioAgentsQuery();

        if(($iAssistantId = bx_get('aid')) !== false) 
            $this->setAssistantId($iAssistantId);
    }

    public function setAssistantId($iAssistantId)
    {
        $this->_iAssistantId = bx_process_input($iAssistantId, BX_DATA_INT);
        $this->_aAssistantInfo = $this->_oDb->getAssistantsBy(['sample' => 'id', 'id' => $this->_iAssistantId]);

        $this->_aQueryAppend['aid'] = $this->_iAssistantId;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->_iAssistantId))
            return [];

        $this->_aOptions['source'] .= $this->_oDb->prepareAsString(" AND `assistant_id`=? ", $this->_iAssistantId);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
