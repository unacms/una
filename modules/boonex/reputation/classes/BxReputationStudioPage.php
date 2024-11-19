<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationStudioPage extends BxBaseModNotificationsStudioPage
{
    public function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_reputation';

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems['manage'] = ['name' => 'manage', 'icon' => 'cogs', 'title' => '_bx_reviews_menu_item_title_manage'];
    }

    protected function getManage()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_MANAGE'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $this->_oModule->_oTemplate->addStudioCss(['manage.css']);
        $this->_oModule->_oTemplate->addStudioJs(['manage.js']);
        $this->_oModule->_oTemplate->addStudioJsTranslation(['_sys_grid_search']);
        return $oGrid->getCode();
    }
}

/** @} */
