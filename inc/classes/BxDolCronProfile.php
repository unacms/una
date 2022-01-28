<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronProfile extends BxDolCron
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processing()
    {
        $this->_processContentFilters();
    }

    protected function _processContentFilters()
    {
        $aProfileModules = bx_srv('system', 'get_modules_by_type', ['profile']);
        if(empty($aProfileModules) || !is_array($aProfileModules))
            return;

        $aProfileTypes = [];
        foreach($aProfileModules as $aProfileModule) 
            $aProfileTypes[] = $aProfileModule['name'];

        $oProfileQuery = BxDolProfileQuery::getInstance();

        $aProfiles = $oProfileQuery->getProfiles(['type' => 'active', 'types' => $aProfileTypes]);
        if(empty($aProfiles) || !is_array($aProfiles))
            return;

        $aFilters = BxDolFormQuery::getDataItems('sys_content_filter', true, BX_DATA_VALUES_ALL);

        foreach($aProfiles as $aProfile) {
            if(!BxDolRequest::serviceExists($aProfile['type'], 'get_info'))
                continue;

            $aProfileInfo = bx_srv($aProfile['type'], 'get_info', [$aProfile['content_id'], false]);

            $iCfwValue = (int)$aProfile['cfw_value'];
            $bUpdateCfwValue = false;

            $iCfwItems = $iCfuItems = 0;
            $bUpdateCfwItems = $bUpdateCfuItems = false;
            foreach($aFilters as $aFilter) {
                $iFilter = (int)$aFilter['Value'];

                if(!empty($aFilter['Data'])) {
                    $aData = unserialize($aFilter['Data']);
                    if(empty($aData) || !is_array($aData))
                        continue;
                }
                else
                    $aData = [
                        'is_allowed_watch' => ['module' => 'system', 'method' => 'is_allowed_cfilter', 'params' => ['watch'], 'class' => 'BaseServiceProfiles'],
                        'is_allowed_use' => ['module' => 'system', 'method' => 'is_allowed_cfilter', 'params' => ['use'], 'class' => 'BaseServiceProfiles'],
                    ];

                if(!empty($aData['is_allowed_watch'])) {
                    $aData['is_allowed_watch']['params'] = array_merge($aData['is_allowed_watch']['params'], [$iFilter, $aProfileInfo]);
                    
                    $iWatch = call_user_func_array('bx_srv', $aData['is_allowed_watch']);
                    if($iWatch === false)
                        continue;

                    if($iWatch == 0) {
                        $iCfwValue &= ~ (1 << ($iFilter - 1));
                        $bUpdateCfwValue = true;
                    }

                    $iCfwItems |= $iWatch;
                    $bUpdateCfwItems = true;
                }

                if(!empty($aData['is_allowed_use']) && (int)$aProfile['cfu_locked'] == 0) {
                    $aData['is_allowed_use']['params'] = array_merge($aData['is_allowed_use']['params'], [$aFilter['Value'], $aProfileInfo]);

                    $iUse = call_user_func_array('bx_srv', $aData['is_allowed_use']);
                    if($iUse === false)
                        continue;

                    $iCfuItems |= $iUse;
                    $bUpdateCfuItems = true;
                }
            }

            if($bUpdateCfwValue) 
                $oProfileQuery->changeCfwValue($aProfile['id'], $iCfwValue);

            if($bUpdateCfwItems)
                $oProfileQuery->changeCfwItems($aProfile['id'], $iCfwItems);

            if($bUpdateCfuItems)
                $oProfileQuery->changeCfuItems($aProfile['id'], $iCfuItems);
        }
    }
}

/** @} */
