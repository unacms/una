<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Directory Directory
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit category form
 */
class BxDirFormCategory extends BxTemplStudioFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_directory';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs['parent_id'])) {
            $this->aInputs['parent_id']['values'] = array();            
        }

        if(isset($this->aInputs['type'])) {
            $this->aInputs['type']['values'] = array();

            $aTypes = $this->_oModule->_oDb->getCategoryTypes(array('type' => 'all'));
            foreach($aTypes as $aType)
                $this->aInputs['type']['values'][] = array('key' => $aType['id'], 'value' => _t($aType['title']));
        }
    }
}

/** @} */
