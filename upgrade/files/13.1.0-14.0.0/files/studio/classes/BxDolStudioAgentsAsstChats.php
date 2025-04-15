<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAsstChats extends BxTemplStudioGrid
{
    protected $_oDb;

    protected $_sCmts;

    protected $_iProfileIdAi;
    protected $_iAssistantId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oDb = new BxDolStudioAgentsQuery();

        $this->_sCmts = 'sys_agents_assistants_chats';

        $this->_iProfileIdAi = BxDolAI::getInstance()->getProfileId();

        if(($iAssistantId = bx_get('aid')) !== false) 
            $this->setAssistantId($iAssistantId);
    }

    public function setAssistantId($iAssistantId)
    {
        $this->_iAssistantId = bx_process_input($iAssistantId, BX_DATA_INT);
        $this->_aQueryAppend['aid'] = $this->_iAssistantId;
    }

    protected function _delete ($mixedId)
    {
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult !== false && ($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $mixedId)) !== null) {
            $oCmts->onObjectDelete($mixedId);
        }

        return $mixedResult;
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
