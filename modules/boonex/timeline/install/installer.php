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

bx_import('BxBaseModNotificationsInstaller');

class BxTimelineInstaller extends BxBaseModNotificationsInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function enable($aParams)
    {
        $aResult = parent::enable($aParams);
        if($aResult['result']) {
            $this->updateParamAET(true);

            $this->updateFeedsMenu();
        }

        return $aResult;
    }

    public function disable($aParams)
    {
        $aResult = parent::disable($aParams);
        if($aResult['result'])
            $this->updateParamAET(false);

        return $aResult;
    }

    protected function updateParamAET($bAdd)
    {
        $sAetDivider = ',';
        $sAetName = 'bx_timeline_send';

        $sAetParam = 'sys_email_attachable_email_templates';
        $sAetParamValue = getParam($sAetParam);

        if($bAdd)
            $sAetParamValue = trim($sAetParamValue . $sAetDivider . $sAetName, $sAetDivider);
        else {
            $aAet = explode($sAetDivider, $sAetParamValue);

            $mixedKey = array_search($sAetName, $aAet);
            if($mixedKey !== false) {
                unset($aAet[$mixedKey]);
                $sAetParamValue = implode($sAetDivider, $aAet);
            }
        }

        return setParam($sAetParam, $sAetParamValue);
    }

    protected function updateFeedsMenu()
    {
        return bx_srv_ii($this->_aConfig['name'], 'feeds_menu_add');
    }
}

/** @} */
