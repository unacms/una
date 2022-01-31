<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

class BxCnlSearchResult extends BxBaseModGroupsSearchResult
{
    function __construct($sMode = '', $aParams = false)
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_channels',
            'module_name' => 'bx_channels',
            'object_metatags' => 'bx_channels',
            'title' => _t('_bx_channels_page_title_browse'),
            'table' => 'sys_profiles',
            'tableSearch' => 'bx_cnl_data',
            'ownFields' => array(),
            'searchFields' => array(),
            'restriction' => array(
                'account_id' => array('value' => '', 'field' => 'account_id', 'operator' => '='),
                'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'perofileType' => array('value' => 'bx_channels', 'field' => 'type', 'operator' => '='),
                'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_cnl_data'),
                'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>', 'table' => 'bx_cnl_data'),
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_cnl_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'channel_name', 'picture', 'cover', 'added', 'author', 'allow_view_to'),
                ),
                'account' => array(
                    'type' => 'INNER',
                    'table' => 'sys_accounts',
                    'mainField' => 'account_id',
                    'onField' => 'id',
                    'joinFields' => array(),
                ),
            ),
            'paginate' => array('perPage' => getParam('bx_channels_per_page_browse'), 'start' => 0),
            'sorting' => 'last',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'channel_name',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'channel_name',
                    'Image' => 'picture',
                ),
            ),
            'ident' => 'id'
        );

        $this->sFilterName = 'bx_cnl_data_filter';
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

            case 'connections':
                if ($this->_setConnectionsConditions($aParams)) {
                    $bProcessConditionsForPrivateContent = false;
                    $oProfile = BxDolProfile::getInstance($aParams['profile']);
                    $oProfile2 = isset($aParams['profile2']) ? BxDolProfile::getInstance($aParams['profile2']) : null;

                    if (isset($aParams['type']) && $aParams['type'] == 'common' && $oProfile && $oProfile2)
                        $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());
                    elseif ((!isset($aParams['type']) || $aParams['type'] != 'common') && $oProfile)
                        $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_connections', $oProfile->getDisplayName());

                    $this->aCurrent['rss']['link'] = 'modules/?r=channels/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];
                }
                break;
                
            case 'followed_entries':
                $oJoinedProfile = BxDolProfile::getInstance((int)$aParams['joined_profile']);
                if (!$oJoinedProfile) {
                    $this->isError = true;
                    break;
                }

                $bProcessConditionsForPrivateContent = false;

                $this->aCurrent['join']['subscriptions'] = array(
                    'type' => 'INNER',
                    'table' => 'sys_profiles_conn_subscriptions',
                    'mainField' => 'id',
                    'onField' => 'content',
                    'joinFields' => array('initiator'),
                );

                $this->aCurrent['restriction']['subscriptions'] = array('value' => $oJoinedProfile->id(), 'field' => 'initiator', 'operator' => '=', 'table' => 'sys_profiles_conn_subscriptions');
                break;

            case 'favorite':
                if(!$this->_setFavoriteConditions($sMode, $aParams, $oJoinedProfile)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'recent':
                $this->aCurrent['rss']['link'] = 'modules/?r=channels/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_recent');
                $this->aCurrent['sorting'] = 'last';
                $this->sBrowseUrl = 'page.php?i=channels-home';
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_featured');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=channels/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'recommended':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_recommended');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=channels/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'recommended';
                $this->_setConditionsForRecommended();
                break;
                
            case 'top':
                $this->aCurrent['rss']['link'] = 'modules/?r=channels/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_channels_page_title_browse_top');
                $this->aCurrent['sorting'] = 'top';
                $this->sBrowseUrl = 'page.php?i=channels-top';
                break;
           
            case 'level':
                $this->aCurrent['join']['level'] = array(
                    'type' => 'INNER',
                    'table' => 'sys_labels',
                    'mainTable' => 'bx_cnl_data',
                    'mainField' => 'channel_name',
                    'onField' => 'value',
                    'joinFields' => array('value'),
                );
                $this->aCurrent['restriction']['level'] = array('value' => $aParams['level'], 'field' => 'level', 'operator' => '=', 'table' => 'sys_labels');
                $this->aCurrent['sorting'] = 'level';
                $this->sBrowseUrl = 'page.php?i=channels-toplevel';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_channels');
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
            return array('order' => ' ORDER BY `bx_cnl_data`.`featured` DESC ');
        case 'recommended':
            return array('order' => ' ORDER BY RAND() ');
        case 'none':
            return array();
        case 'top':
            return array('order' => ' ORDER BY `bx_cnl_data`.`views` DESC ');
        case 'level':
            return array('order' => ' ORDER BY `bx_cnl_data`.`channel_name` DESC ');
        case 'last':
        default:
            return array('order' => ' ORDER BY `bx_cnl_data`.`added` DESC ');
        }
    }

    function _getPseud ()
    {
        return array(
            'id' => 'id',
            'channel_name' => 'channel_name',
            'added' => 'added',
            'author' => 'author',
            'picture' => 'picture',
        );
    }
}

/** @} */
