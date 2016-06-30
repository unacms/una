<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOrgsSearchResult extends BxBaseModProfileSearchResult
{
    function __construct($sMode = '', $aParams = false)
    {
        parent::__construct($sMode, $aParams);

        $this->sUnitTemplate = 'unit_with_cover.html';

        $this->aCurrent =  array(
            'name' => 'bx_organizations',
            'module_name' => 'bx_organizations',
            'object_metatags' => 'bx_organizations',
            'title' => _t('_bx_orgs_page_title_browse'),
            'table' => 'sys_profiles',
            'tableSearch' => 'bx_organizations_data',
            'ownFields' => array(),
            'searchFields' => array('org_name', 'org_desc'),
            'restriction' => array(
        		'account_id' => array('value' => '', 'field' => 'account_id', 'operator' => '='),
                'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'perofileType' => array('value' => 'bx_organizations', 'field' => 'type', 'operator' => '='),
                'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_organizations_data'),
        		'online' => array('value' => '', 'field' => 'date', 'operator' => '>', 'table' => 'sys_sessions'),
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_organizations_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'org_name', 'picture', 'cover', 'added', 'author', 'allow_view_to'),
                ),
                'account' => array(
                    'type' => 'INNER',
                    'table' => 'sys_accounts',
                    'mainField' => 'account_id',
                    'onField' => 'id',
                    'joinFields' => array(),
                ),
            ),
            'paginate' => array('perPage' => 20, 'start' => 0),
            'sorting' => 'none',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'org_name',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'org_name',
                    'Picture' => 'picture',
                ),
            ),
            'ident' => 'id'
        );

        $this->sFilterName = 'bx_organizations_data_filter';
        $this->oModule = $this->getMain();

        switch ($sMode) {

            case 'connections':
                if ($this->_setConnectionsConditions($aParams)) {
                    $oProfile = BxDolProfile::getInstance($aParams['profile']);
                    $oProfile2 = isset($aParams['profile2']) ? BxDolProfile::getInstance($aParams['profile2']) : null;

                    if (isset($aParams['type']) && $aParams['type'] == 'common' && $oProfile && $oProfile2)
                        $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());
                    elseif ((!isset($aParams['type']) || $aParams['type'] != 'common') && $oProfile)
                        $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_connections', $oProfile->getDisplayName());

                    $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];
                }
                break;

            case 'recent':
                $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_recent');
                $this->aCurrent['sorting'] = 'last';
                $this->sBrowseUrl = 'page.php?i=organizations-home';
                break;

            case 'active':
                $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_active');
                $this->aCurrent['sorting'] = 'active';
                $this->sBrowseUrl = 'page.php?i=organizations-active';
                break;

			case 'online':
                $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_online');
                $this->aCurrent['restriction']['online']['value'] = time() - 60 * (int)getParam('sys_account_online_time');
                $this->aCurrent['restriction_sql'] = ' AND `sys_accounts`.`profile_id`=`sys_profiles`.`id`';
                $this->aCurrent['join']['session'] = array(
                    'type' => 'INNER',
                    'table' => 'sys_sessions',
                    'mainField' => 'account_id',
                    'onField' => 'user_id',
                    'joinFields' => array('date'),
                );
                $this->aCurrent['sorting'] = 'online';
                $this->sBrowseUrl = 'page.php?i=organizations-online';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_orgs');
                $this->aCurrent['paginate']['perPage'] = 5;
                unset($this->aCurrent['rss']);
                break;

            default:
                $this->isError = true;
        }

        $this->sCenterContentUnitSelector = false;
    }

    function getAlterOrder()
    {
        switch ($this->aCurrent['sorting']) {
	        case 'none':
	            return array('order' => ' ORDER BY `sys_accounts`.`logged` DESC ');
			case 'active':
	            return array('order' => ' ORDER BY `sys_accounts`.`logged` DESC ');
			case 'online':
	            return array('order' => ' ORDER BY `sys_sessions`.`date` DESC ');
	        case 'last':
	        default:                        
	            return array('order' => ' ORDER BY `bx_organizations_data`.`added` DESC ');
        }
    }

    function _getPseud ()
    {
        return array(
            'id' => 'id',
            'org_name' => 'org_name',
            'added' => 'added',
            'author' => 'author',
            'picture' => 'picture',
        );
    }
}

/** @} */
