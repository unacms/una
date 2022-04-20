<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineFormRepost extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    protected $_aReposted;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $sAction = trim(str_replace($this->_sModule, '', $this->aParams['display']), "_");
        $this->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . $sAction . '/';

        $this->_aReposted = [];
    }

    public function getReposted()
    {
        return $this->_aReposted;
    }

    public function initChecker($aValues = [], $aSpecificValues = [])
    {
        if(isset($aValues['type'], $aValues['action'], $aValues['object_id']))
            $this->_aReposted = $this->_oModule->_oDb->getReposted($aValues['type'], $aValues['action'], $aValues['object_id']);

        return parent::initChecker($aValues, $aSpecificValues);
    }

    protected function genCustomInputReposted($aInput)
    {
        return $this->_oModule->_oTemplate->getRepostWithFieldReposted($this, $aInput);
    }

    protected function genCustomInputSearch($aInput)
    {
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'get_contexts';

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }

    protected function genCustomInputList($aInput)
    {
        return $this->_oModule->_oTemplate->getRepostToFieldList($this, $aInput);
    }
}

/** @} */
