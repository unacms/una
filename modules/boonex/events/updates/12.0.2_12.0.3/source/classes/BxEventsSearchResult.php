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
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_events_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'event_name', 'event_desc', 'picture', 'cover', 'added', 'author', 'allow_view_to', 'date_start', 'date_end'),
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

        if ($bProcessConditionsForPrivateContent)
            $this->addConditionsForPrivateContent($CNF, $oJoinedProfile);

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
