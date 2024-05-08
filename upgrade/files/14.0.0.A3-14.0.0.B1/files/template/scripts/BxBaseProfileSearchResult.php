<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseProfileSearchResult extends BxTemplSearchResult
{
    protected $_bIsApi;

    protected $aUnitViews;
    protected $sUnitViewDefault;

    public function __construct($sMode = '', $aParams = [])
    {
        $this->_sMode = $sMode;
        $this->_aParams = $aParams;

        $this->_bValidate = !empty($this->_aParams['validate']) && is_array($this->_aParams['validate']);

        parent::__construct();

        $this->_bIsApi = bx_is_api();

        $this->aUnitViews = [
            'unit' => 'unit_wo_cover.html', 
            'gallery' => 'unit_with_cover.html'
        ];
        if(!empty($aParams['unit_views']) && is_array($aParams['unit_views']))
            $this->aUnitViews = array_merge($this->aUnitViews, $aParams['unit_views']);

        $this->sUnitViewDefault = 'gallery';
        if(!empty($aParams['unit_view']))
            $this->sUnitViewDefault = $aParams['unit_view'];

        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];

        $this->addContainerClass (array(
            'bx-base-pofile-units-wrapper', 
            'bx-base-puw-' . str_replace('_' ,  '-', $this->sUnitViewDefault),
            'bx-def-margin-sec-neg'
        ));

        $aProfileModules = bx_srv('system', 'get_modules_by_type', ['profile']);
        $aProfileModulesNames = array_map(function($item) {
            return $item['name'];
        }, $aProfileModules);

        $this->aCurrent = [
            'name' => 'sys_search',
            'module_name' => 'system',
            'title' => '',
            'table' => 'sys_profiles',
            'ownFields' => ['id', 'account_id', 'type', 'content_id'],
            'searchFields' => [],
            'restriction' => [
                'account_id' => ['value' => '', 'field' => 'account_id', 'operator' => '='],
                'perofileStatus' => ['value' => 'active', 'field' => 'status', 'operator' => '='],
                'perofileType' => ['value' => $aProfileModulesNames, 'field' => 'type', 'operator' => 'in'],
            ],
            'join' => [
                'account' => [
                    'type' => 'INNER',
                    'table' => 'sys_accounts',
                    'mainField' => 'account_id',
                    'onField' => 'id',
                    'joinFields' => [],
                ],
            ],
            'paginate' => ['start' => 0, 'perPage' => BX_DOL_SEARCH_RESULTS_PER_PAGE_DEFAULT],
            'sorting' => 'last',
            'ident' => 'id'
        ];

        if(!empty($this->_aParams['start']))
            $this->aCurrent['paginate']['forceStart'] = (int)$this->_aParams['start'];
        if(!empty($this->_aParams['per_page']))
            $this->aCurrent['paginate']['perPage'] = (int)$this->_aParams['per_page'];

        $this->sBrowseUrl = '';

        switch ($sMode) {
            case 'friends':
                $aParams = array_merge($aParams, [
                    'object' => 'sys_profiles_friends', 
                    'type' => 'content',
                    'mutual' => true,
                    'profile2' => 0
                ]);
                
                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_friends/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]='
                ]);
                break;

            case 'friend_requests':
                $aParams = array_merge($aParams, [
                    'object' => 'sys_profiles_friends', 
                    'type' => 'initiators',
                    'mutual' => 0,
                    'profile2' => 0
                ]);
                
                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_friend_requests/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]='
                ]);
                break;
                
            case 'friend_requested':
                $aParams = array_merge($aParams, [
                    'object' => 'sys_profiles_friends', 
                    'type' => 'content',
                    'mutual' => 0,
                    'profile2' => 0
                ]);
                
                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_friend_requested/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]='
                ]);
                break;
                
            case 'subscriptions':
                $aParams = array_merge($aParams, [
                    'object' => 'sys_profiles_subscriptions', 
                    'type' => 'content',
                    'mutual' => false,
                    'profile2' => 0
                ]);

                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_subscriptions/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]='
                ]);
                break;
            
            case 'subscribed_me':
                $aParams = array_merge($aParams, [
                    'object' => 'sys_profiles_subscriptions', 
                    'type' => 'initiators',
                    'mutual' => false,
                    'profile2' => 0
                ]);

                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_subscribed_me/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]='
                ]);
                break;

            case 'members':
                $aParams = array_merge($aParams, [
                    'type' => 'content',
                    'mutual' => true,
                    'profile2' => 0
                ]);

                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $this->aCurrent = array_merge($this->aCurrent, [
                    'title' => _t('_sys_page_title_browse_connections', BxDolProfile::getInstanceMagic($aParams['profile'])->getDisplayName()),
                    'api_request_url' => '/api.php?r=system/browse_members/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]=' . $aParams['object'] . '&params[]='
                ]);
                break;

            case 'connections':
                if (!$this->_setConnectionsConditions($aParams)) 
                    break;

                $oProfile = BxDolProfile::getInstance($aParams['profile']);
                $oProfile2 = isset($aParams['profile2']) ? BxDolProfile::getInstance($aParams['profile2']) : null;

                $bCommon = isset($aParams['type']) && $aParams['type'] == 'common';
                if($bCommon && $oProfile && $oProfile2)
                    $this->aCurrent['title'] = _t('_sys_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());
                else if(!$bCommon && $oProfile)
                    $this->aCurrent['title'] = _t('_sys_page_title_browse_connections', $oProfile->getDisplayName());

                $this->aCurrent['api_request_url'] = '/api.php?r=system/browse_connections/TemplServiceProfiles&params[]=' . $aParams['profile'] . '&params[]=';
                break;
        }
    }

    function displaySearchUnit ($aData)
    {
        $oProfile = BxDolProfile::getInstance($aData['id']);
        if(!$oProfile)
            return '';

        $aParams = $this->aUnitParams;

        if($this->_bIsApi)
            return $oProfile->getUnitAPI(0, $aParams);

        $aParams['template'] = substr($this->sUnitTemplate, 0, -5);
        return $oProfile->getUnit(0, $aParams);
    }

    function getAlterOrder()
    {
        $sType = is_array($this->aCurrent['sorting']) ? $this->aCurrent['sorting']['type'] : $this->aCurrent['sorting'];

        switch($sType) {
            case 'none':
                return [];

            case 'active':
                return ['order' => ' ORDER BY `sys_accounts`.`logged` DESC '];

            case 'last_connected':
                return ['order' => ' ORDER BY `' . $this->aCurrent['sorting']['table'] . '`.`added` DESC '];

            case 'last':
            default:
                return ['order' => ' ORDER BY `sys_accounts`.`added` DESC '];
        }
    }

    function decodeDataAPI($a)
    {
        if(!is_array($a))
            return $a;

        $aResult = [];

        foreach ($a as $index => $aItem) 
            if(($oProfile = BxDolProfile::getInstance($aItem['id'])) !== false) {
                $aUnitApi = $oProfile->getUnitAPI();
                $aUnitApi['id'] = $aItem['id'];

                $aResult[] = $aUnitApi;
            }

        return $aResult;
    }

    protected function _setConnectionsConditions ($aParams)
    {
        $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
        if(!$oConnection || empty($aParams['profile']))
            return false;

        $sContentType = isset($aParams['type']) ? $aParams['type'] : BX_CONNECTIONS_CONTENT_TYPE_CONTENT;
        $isMutual = isset($aParams['mutual']) ? $aParams['mutual'] : false;
        $aConnection = $oConnection->getConnectionsAsCondition($sContentType, 'id', (int)$aParams['profile'], (int)$aParams['profile2'], $isMutual);

        $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aConnection['restriction']);
        $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aConnection['join']);

        $this->aCurrent['sorting'] = [
            'type' => 'last_connected',
            'table' => current($aConnection['join'])['table']
        ];

        return true;
    }
}

/** @} */
