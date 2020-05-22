<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxTasksMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
        parent::__construct($aObject, $oTemplate);
		$this->addMarkers(array('js_object' => $this->_oModule->_oConfig->getJsObject('tasks')));
    }

    protected function _getMenuItemEditTask($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteTask($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
