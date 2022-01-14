<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModNotificationsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($sModule, $mixedPageName, $sPage = "")
    {
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);
    }

    public function checkAction()
    {
    	$sAction = bx_get($this->sParamPrefix . '_action');
    	if($sAction === false)
            return false;

        if(empty($this->aModule) || !is_array($this->aModule))
            return array('code' => 1, 'message' => _t('_sys_request_page_not_found_cpt'));

        $sAction = bx_process_input($sAction);

        $aResult = array('code' => 2, 'message' => _t('_adm_mod_err_cannot_process_action'));
        switch($sAction) {
            case 'reinit':
                $aConfig = BxDolInstallerUtils::getModuleConfig($this->_oModule->_aModule);
                if(empty($aConfig) || !is_array($aConfig))
                    break;

                $oInstallerDb = bx_instance('BxDolStudioInstallerQuery');               

                $aRelation = $oInstallerDb->getRelationsBy(array('type' => 'module', 'value' => $aConfig['name']));
                if(empty($aRelation))
                    break;

                $aOperations = ['disable', 'enable'];
                foreach($aOperations as $sOperation) {
                    if(empty($aRelation['on_' . $sOperation]))
                        break 2;

                    $aModules = $oInstallerDb->getModulesBy(array('type' => 'all', 'active' => 1));
                    foreach($aModules as $aModule) {
                        $aModuleConfig = BxDolInstallerUtils::getModuleConfig($aModule);
                        if(!empty($aModuleConfig['relations']) && is_array($aModuleConfig['relations']) && in_array($aConfig['name'], $aModuleConfig['relations']))
                            bx_srv_ii($aConfig['name'], $aRelation['on_' . $sOperation], [$aModule['uri']]);
                    }
                }

                $aResult = ['code' => 0, 'message' => _t('_adm_txt_modules_process_action_success')];
                break;

            default:
                $aResult = parent::checkAction();
        }

        return $aResult;
    }
}

/** @} */
