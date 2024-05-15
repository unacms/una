<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAutomators extends BxTemplStudioGrid
{
    protected $_oDb;

    protected $_sCmts;

    protected $_iProfileIdAi;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oDb = new BxDolStudioAgentsQuery();

        $this->_sCmts = 'sys_agents_automators';

        $this->_iProfileIdAi = BxDolAI::getInstance()->getProfileId();
    }

    protected function _delete ($mixedId)
    {
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult !== false && ($oCmts = BxDolCmts::getObjectInstance($this->_sCmts, $mixedId)) !== null) {
            $oCmts->onObjectDelete($mixedId);
        }

        return $mixedResult;
    }
}

/** @} */
