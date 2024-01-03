<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsSearchResult extends BxBaseModGroupsSearchResult
{
    function __construct($sMode = '', $aParams = false)
    {
        parent::__construct($sMode, $aParams); 

        $this->aCurrent =  array(
            'name' => 'bx_events',
            'module_name' => 'bx_events',
            'object_metatags' => 'bx_events',
            'title' => _t('_bx_events_page_title_browse'),
            'table' => 'sys_profiles',
            'tableSearch' => 'bx_events_data',
            'ownFields' => array(),
            'searchFields' => array(),
            'restriction' => array(
                'account_id' => array('value' => '', 'field' => 'account_id', 'operator' => '='),
                'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'perofileType' => array('value' => 'bx_events', 'field' => 'type', 'operator' => '='),
                'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_events_data'),
                'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>', 'table' => 'bx_events_data'),
                'upcoming' => array('value' => '', 'field' => 'date_end', 'operator' => '>', 'table' => 'bx_events_data'),
                'past' => array('value' => '', 'field' => 'date_end', 'operator' => '<', 'table' => 'bx_events_data'),
                'date_start_from' => array('value' => '', 'field' => 'date_start', 'operator' => '>=', 'table' => 'bx_events_data'),
                'date_start_to' => array('value' => '', 'field' => 'date_start', 'operator' => '<=', 'table' => 'bx_events_data'),
                'status' => array('value' => 'active', 'field' => 'status', 'operator' => '=', 'table' => 'bx_events_data'),
                'statusAdmin' => array('value' => 'active', 'field' => 'status_admin', 'operator' => '=', 'table' => 'bx_events_data'),
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_events_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'event_name', 'event_desc', 'picture', 'cover', 'cover_data', 'added', 'author', 'allow_view_to', 'date_start', 'date_end', 'threshold'),
                ),
                'account' => array(
                    'type' => 'INNER',
                    'table' => 'sys_accounts',
                    'mainField' => 'account_id',
                    'onField' => 'id',
                    'joinFields' => array(),
                ),
            ),
            'paginate' => array('perPage' => getParam('bx_events_per_page_browse'), 'start' => 0),
            'sorting' => 'last',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'event_name',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'event_name',
                    'Image' => 'picture',
                ),
            ),
            'ident' => 'id'
        );

        $this->sFilterName = 'bx_events_data_filter';
        $this->oModule = $this->getMain();

        $CNF = &$this->oModule->_oConfig->CNF;

        $sSearchFields = getParam($CNF['PARAM_SEARCHABLE_FIELDS']);
        $this->aCurrent['searchFields'] = !empty($sSearchFields) ? explode(',', $sSearchFields) : '';

        $oJoinedProfile = null;
        $bProcessConditionsForPrivateContent = true;

        if(isset($this->_aParams['by_city'])) {
            $this->setMetaType('location_city');
            $this->setCustomSearchCondition(['keyword' => $this->_aParams['by_city']]);
        }

        $iDateStart = $iDateEnd = 0;
        if(isset($this->_aParams['by_date'])) {
            $iTimezoneOffset = 0;
            if(!empty($this->_aParams['timezone']) && in_array($this->_aParams['timezone'], timezone_identifiers_list()))
                $iTimezoneOffset = date_offset_get(date_create('now', timezone_open($this->_aParams['timezone'])));

            switch($this->_aParams['by_date']) {
                case 'today':
                    $iDateStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                    $iDateEnd = $iDateStart + 86399;
                    break;

                case 'tomorrow':
                    $iDateStart = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
                    $iDateEnd = $iDateStart + 86399;
                    break;

                case 'weekend':
                    $iDayOfWeek = date("w");
                    $iDateStart = mktime(0, 0, 0, date("m"), date("d") + ($iDayOfWeek > 0 ? 6 - $iDayOfWeek : -1), date("Y"));
                    $iDateEnd = $iDateStart + 2 * 86400 - 1;
                    break;

                case 'this_week':
                    $iDayOfWeek = date("w");
                    $iDateStart = mktime(0, 0, 0, date("m"), date("d") - ($iDayOfWeek > 0 ? $iDayOfWeek - 1 : 6), date("Y"));
                    $iDateEnd = $iDateStart + 7 * 86400 - 1;
                    break;

                case 'next_week':
                    $iDayOfWeek = date("w");
                    $iDateStart = mktime(0, 0, 0, date("m"), date("d") + ($iDayOfWeek > 0 ? 8 - $iDayOfWeek : 1), date("Y"));
                    $iDateEnd = $iDateStart + 7 * 86400 - 1;
                    break;

                case 'this_month':
                    $iDateStart = mktime(0, 0, 0, date("m"), 1, date("Y"));
                    $iDateEnd = mktime(23, 59, 59, date("m") + 1, 0, date("Y"));
                    break;

                case 'date_range':
                    if(!empty($this->_aParams['date_start'])) {
                        list($iDsy, $iDsm, $iDsd,) = explode('-', $this->_aParams['date_start']);
                        $iDateStart = mktime(0, 0, 0, $iDsm, $iDsd, $iDsy);
                    }
                    else
                        $iDateStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

                    if(!empty($this->_aParams['date_end'])) {
                        list($iDey, $iDem, $iDed,) = explode('-', $this->_aParams['date_end']);
                        $iDateEnd = mktime(23, 59, 59, $iDem, $iDed, $iDey);
                    }
                    else
                        $iDateEnd = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
                    break;
            }

            if($iDateStart != 0 && $iDateEnd != 0) {
                $iDateStart -= $iTimezoneOffset;
                $iDateEnd -= $iTimezoneOffset;

                //--- 1. already started or will start in selected date interval
                $this->aCurrent['restriction']['filter_date_start'] = ['value' => $iDateEnd, 'field' => 'date_start', 'operator' => '<', 'table' => 'bx_events_data'];
                //--- 2. and didn't finished to the beginning of selected date interval
                $this->aCurrent['restriction']['filter_date_end'] = ['value' => $iDateStart, 'field' => 'date_end', 'operator' => '>', 'table' => 'bx_events_data'];
            }
        }

        switch ($sMode) {
            case 'created_entries':
                if(!$this->_setAuthorConditions($sMode, $aParams, $oJoinedProfile)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'context':
                $oProfileAuthor = null;
                if(!$this->_updateCurrentForContext($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;
                break;

            case 'joined_entries':
                $oJoinedProfile = BxDolProfile::getInstance((int)$aParams['joined_profile']);
                if (!$oJoinedProfile) {
                    $this->isError = true;
                    break;
                }

                $bProcessConditionsForPrivateContent = false;

                $this->aCurrent['join']['fans'] = array(
                    'type' => 'INNER',
                    'table' => 'bx_events_fans',
                    'mainField' => 'id',
                    'onField' => 'content',
                    'joinFields' => array('initiator'),
                );

                $this->aCurrent['restriction']['fans'] = array('value' => $oJoinedProfile->id(), 'field' => 'initiator', 'operator' => '=', 'table' => 'bx_events_fans');

                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_JOINED_ENTRIES'] . '&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_events_page_title_joined_entries');
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode . '/' . $oJoinedProfile->id();
                break;
                
            case 'followed_entries':
                $oJoinedProfile = BxDolProfile::getInstance((int)$aParams['followed_profile']);
                if (!$oJoinedProfile) {
                    $this->isError = true;
                    break;
                }

                $bProcessConditionsForPrivateContent = false;

                $this->aCurrent['join']['followed'] = array(
                    'type' => 'INNER',
                    'table' => 'sys_profiles_conn_subscriptions',
                    'mainField' => 'id',
                    'onField' => 'content',
                    'joinFields' => array('initiator'),
                );

                $this->aCurrent['restriction']['followed'] = array('value' => $oJoinedProfile->id(), 'field' => 'initiator', 'operator' => '=', 'table' => 'sys_profiles_conn_subscriptions');

                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_FOLLOWED_ENTRIES'] . '&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_groups_page_title_followed_entries');
                $this->aCurrent['rss']['link'] = 'modules/?r=groups/rss/' . $sMode . '/' . $oJoinedProfile->id();
                break;

            case 'connections':
                if ($this->_setConnectionsConditions($aParams)) {
                    $bProcessConditionsForPrivateContent = false;
                    $oProfile = BxDolProfile::getInstance($aParams['profile']);
                    $oProfile2 = isset($aParams['profile2']) ? BxDolProfile::getInstance($aParams['profile2']) : null;

                    if (isset($aParams['type']) && $aParams['type'] == 'common' && $oProfile && $oProfile2)
                        $this->aCurrent['title'] = _t('_bx_events_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());
                    elseif ((!isset($aParams['type']) || $aParams['type'] != 'common') && $oProfile)
                        $this->aCurrent['title'] = _t('_bx_events_page_title_browse_connections', $oProfile->getDisplayName());

                    $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];
                }
                break;

            case 'favorite':
                if(!$this->_setFavoriteConditions($sMode, $aParams, $oJoinedProfile)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'recent':
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_recent');
                $this->aCurrent['sorting'] = 'last';
                $this->sBrowseUrl = 'page.php?i=events-home';
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_featured');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'recommended':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_recommended');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'recommended';
                $this->_setConditionsForRecommended();
                break;    
                
            case 'top':
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_top');
                $this->aCurrent['sorting'] = 'top';
                $this->sBrowseUrl = 'page.php?i=events-top';
                break;

            case 'upcoming':
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_upcoming');
                $this->aCurrent['restriction']['upcoming']['value'] = time();
                if(!empty($aParams['date_start_from']))
                    $this->aCurrent['restriction']['date_start_from']['value'] = $aParams['date_start_from'];
                if(!empty($aParams['date_start_to']))
                    $this->aCurrent['restriction']['date_start_to']['value'] = $aParams['date_start_to'];
                $this->aCurrent['sorting'] = 'upcoming';
                if(!empty($aParams['per_page']))
                    $this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);
                $this->sBrowseUrl = 'page.php?i=events-upcoming';
                break;

            case 'upcoming_connected':
                if(!$this->_setConnectionsConditions($aParams)) 
                    break;

                $bProcessConditionsForPrivateContent = false;

                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/upcoming';
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_upcoming');
                $this->aCurrent['restriction']['upcoming']['value'] = time();
                if(!empty($aParams['date_start_from']))
                    $this->aCurrent['restriction']['date_start_from']['value'] = $aParams['date_start_from'];
                if(!empty($aParams['date_start_to']))
                    $this->aCurrent['restriction']['date_start_to']['value'] = $aParams['date_start_to'];
                $this->aCurrent['sorting'] = 'upcoming';
                if(!empty($aParams['per_page']))
                    $this->aCurrent['paginate']['perPage'] = is_numeric($aParams['per_page']) ? (int)$aParams['per_page'] : (int)getParam($aParams['per_page']);
                $this->sBrowseUrl = 'page.php?i=events-upcoming';
                break;

            case 'past':
                $this->aCurrent['rss']['link'] = 'modules/?r=events/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_events_page_title_browse_past');
                $this->aCurrent['restriction']['past']['value'] = time();
                $this->aCurrent['sorting'] = 'past';
                $this->sBrowseUrl = 'page.php?i=events-past';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_events');
                unset($this->aCurrent['paginate']['perPage'], $this->aCurrent['rss']);
                break;

            default:
                $this->isError = true;
        }

        if ($bProcessConditionsForPrivateContent) {
            $this->addConditionsForPrivateContent($CNF, $oJoinedProfile);
            
            $this->addCustomConditions($CNF, $oJoinedProfile, $sMode, $aParams);
        }

        $this->sCenterContentUnitSelector = false;
    }

    function getAlterOrder()
    {
        switch ($this->aCurrent['sorting']) {
        case 'featured':
            return array('order' => ' ORDER BY `bx_events_data`.`featured` DESC ');
        case 'recommended':
            return array('order' => ' ORDER BY RAND() ');
        case 'none':
            return array();
        case 'top':
            return array('order' => ' ORDER BY `bx_events_data`.`views` DESC ');
        case 'upcoming':
            return array('order' => ' ORDER BY `bx_events_data`.`date_start` ASC, `bx_events_data`.`date_end` ASC ');
        case 'past':
            return array('order' => ' ORDER BY `bx_events_data`.`date_start` DESC, `bx_events_data`.`date_end` DESC ');
        case 'last':
        default:                        
            return array('order' => ' ORDER BY `bx_events_data`.`added` DESC ');
        }
    }

    function _getPseud ()
    {
        return array(
            'id' => 'id',
            'event_name' => 'event_name',
            'added' => 'added',
            'author' => 'author',
            'picture' => 'picture',
        );
    }
}

/** @} */
