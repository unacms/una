<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

class BxElsInstaller extends BxDolStudioInstaller
{
    protected $_oDb;

    function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_aActions = array_merge($this->_aActions, array(
        	'process_content_alerts' => array(
				'title' => 'Process content alerts',
            ),
        ));

        $this->_oDb = BxDolDb::getInstance();
    }

    public function actionProcessContentAlerts($sOperation)
    {
        if($sOperation != 'enable')
            return BX_DOL_STUDIO_INSTALLER_SUCCESS;

        $aCiObjects = BxDolContentInfo::getSystems();
        if(empty($aCiObjects) || !is_array($aCiObjects))
            return BX_DOL_STUDIO_INSTALLER_SUCCESS;

        $iHandlerId = $this->_oDb->getOne("SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=:name", array(
            'name' => $this->_aConfig['name']
        ));

        $aActions = array('add', 'update', 'delete');
        foreach($aCiObjects as $aCiObject)
            foreach($aActions as $sAction)
                $this->_oDb->query("INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES (:unit, :action, :handler);", array(
                    'unit' => $aCiObject['alert_unit'],
                	'action' => $aCiObject['alert_action_' . $sAction],
                    'handler' => $iHandlerId
                ));

        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }
}

/** @} */
