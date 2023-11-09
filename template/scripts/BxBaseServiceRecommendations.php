<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for recommendations.
 */
class BxBaseServiceRecommendations extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceUpdateData($iProfileId, $bVerbose = false)
    {
        $aResults = [];

        $aObjects = BxDolRecommendationQuery::getObjects();
        foreach($aObjects as $aObject)
            if(($oRecommendation = BxDolRecommendation::getObjectInstance($aObject['name'])) !== false)
                $aResults[] = [
                    'object' => $aObject['name'],
                    'items' => $oRecommendation->processCriteria($iProfileId)
                ];

        if($bVerbose)
            return $aResults;
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-recommendations Recommendations
     * @subsubsection bx_system_general-perform perform
     * 
     * @code bx_srv('system', 'perform', [[...]], 'TemplServiceRecommendations'); @endcode
     * @code {{~system:perform:TemplServiceRecommendations[[...]]~}} @endcode
     * 
     * Performs an action (add, remove, etc) with recommendations object.
     * @param $aParams an array with necessary parameters 
     * 
     * @see BxBaseServiceRecommendations::servicePerform
     */
    /** 
     * @ref bx_system_general-perform "perform"
     * @api @ref bx_system_general-perform "perform"
     */
    public function servicePerform($aParams)
    {
        if(is_string($aParams))
            $aParams = json_decode($aParams, true);

        if(!$aParams['o'] || !$aParams['a'] || !$aParams['iid'] || !$aParams['cid'])
            return ['code' => 1];

        $oRecommendation = BxDolRecommendation::getObjectInstance($aParams['o']);
        if(!$oRecommendation)
            return ['code' => 2];

        $sMethod = 'action' . bx_gen_method_name($aParams['a']);
        if(!method_exists($oRecommendation, $sMethod))
            return ['code' => 2];

        $aResult = $oRecommendation->$sMethod($aParams['iid'], $aParams['cid']);
        if($aResult['code'] != 0)
            return ['code' => 3, 'message' => $aResult['msg']];

        $aFlip = [
            'add' => '',
            'ignore' => ''
        ];

        $sFlipped = $aFlip[$aParams['a']];
        return [
            'a' => $sFlipped,
            'title' => !empty($sFlipped) ? _t('_sys_menu_item_title_sm_' . $sFlipped) : '',
        ];
    }

    public function serviceGetFriendRecommendationsBySharedContext($iProfileId, $sConnection, $iPoints)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return [];
        
        $oConnection = BxDolConnection::getObjectInstance($sConnection);
        if(!$oConnection)
            return [];

        $sQuery = "SELECT `tm`.`initiator`AS `id`, SUM({points}) AS `value` FROM `{connection}` AS `tg` INNER JOIN `{connection}` AS `tm` ON `tg`.`content`=`tm`.`content` AND `tm`.`initiator`<>{profile_id} AND `tm`.`initiator` NOT IN (SELECT `content` FROM `sys_profiles_conn_friends` WHERE `initiator`={profile_id} AND `mutual`='1') AND `tm`.`mutual`='1' WHERE `tg`.`initiator`={profile_id} AND `tg`.`mutual`='1' GROUP BY `id`";
        $sQuery = bx_replace_markers($sQuery, [
            'profile_id' => $iProfileId,
            'connection' => $oConnection->getTable(),
            'points' => $iPoints
        ]);

        return BxDolDb::getInstance()->getPairs($sQuery, 'id', 'value');
    }

    public function serviceGetFriendRecommendationsBySharedLocation($iProfileId, $iRadius, $iPoints)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return [];

        $sProfileModule = $oProfile->getModule();
        $iProfileContentId = $oProfile->getContentId();

        $aLocation = bx_srv($sProfileModule, 'get_location', [$iProfileContentId]);
        if(empty($aLocation) || !is_array($aLocation))
            return [];

        $aIds = bx_srv('system', 'profiles_search_by_location', [$aLocation, $iRadius], 'TemplServiceProfiles');
        if(empty($aIds) || !is_array($aIds))
            return [];

        /**
         * Exclude friends and oneself
         */
        $aIdsExclude = BxDolConnection::getObjectInstance('sys_profiles_friends')->getConnectedContent($iProfileId);
        $aIdsExclude[] = $iProfileId;

        $aIds = array_diff($aIds, $aIdsExclude);
        return array_combine($aIds, array_fill(0, count($aIds), $iPoints));
    }

    public function serviceGetSubscriptionRecommendationsBySharedContext($iProfileId, $sConnection, $iPoints)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return [];

        $oConnection = BxDolConnection::getObjectInstance($sConnection);
        if(!$oConnection)
            return [];

        $sQuery = "SELECT `tm`.`initiator`AS `id`, SUM({points}) AS `value` FROM `{connection}` AS `tg` INNER JOIN `{connection}` AS `tm` ON `tg`.`content`=`tm`.`content` AND `tm`.`initiator`<>{profile_id} AND `tm`.`initiator` NOT IN (SELECT `content` FROM `sys_profiles_conn_subscriptions` WHERE `initiator`={profile_id}) AND `tm`.`mutual`='1' WHERE `tg`.`initiator`={profile_id} AND `tg`.`mutual`='1' GROUP BY `id`";
        $sQuery = bx_replace_markers($sQuery, [
            'profile_id' => $iProfileId,
            'connection' => $oConnection->getTable(),
            'points' => $iPoints
        ]);

        return BxDolDb::getInstance()->getPairs($sQuery, 'id', 'value');
    }
}

/** @} */
