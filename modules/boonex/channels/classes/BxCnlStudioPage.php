<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCnlStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_channels';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);
    }

    protected function getSettings()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMsg = '';
        if((int)getParam($CNF['PARAM_DEFAULT_AUTHOR']) == 0)
            $sMsg .= MsgBox(_t('_bx_channels_msg_empty_author'));

        return $sMsg . parent::getSettings();
    }
}

/** @} */
