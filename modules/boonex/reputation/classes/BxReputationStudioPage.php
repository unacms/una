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

        $this->aMenuItems = array_merge($this->aMenuItems, [
            'handlers' => ['name' => 'manage', 'icon' => 'cogs', 'title' => '_bx_reviews_menu_item_title_handlers'],
            'levels' => ['name' => 'levels', 'icon' => 'cogs', 'title' => '_bx_reviews_menu_item_title_levels'],
        ]);
    }

    protected function getHandlers()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_HANDLERS'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $this->_oModule->_oTemplate->addStudioCss(['handlers.css']);
        $this->_oModule->_oTemplate->addStudioJs(['handlers.js']);
        $this->_oModule->_oTemplate->addStudioJsTranslation(['_sys_grid_search']);
        return $oGrid->getCode();
    }

    protected function getLevels()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_LEVELS'], BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $this->_oModule->_oTemplate->addStudioCss(['levels.css']);
        $this->_oModule->_oTemplate->addStudioJs(['levels.js']);
        return $oGrid->getCode();
    }
}

/** @} */
