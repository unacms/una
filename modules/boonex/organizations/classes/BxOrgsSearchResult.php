<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModProfileSearchResult');

class BxOrgsSearchResult extends BxBaseModProfileSearchResult 
{
    function __construct($sMode = '', $aParams = false) 
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent =  array(
            'name' => 'bx_organizations',
            'title' => _t('_bx_orgs_page_title_browse'),
            'table' => 'sys_profiles',
            'ownFields' => array(),
            'searchFields' => array('org_name'),
            'restriction' => array(
                'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'perofileType' => array('value' => 'bx_organizations', 'field' => 'type', 'operator' => '='),
                'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_organizations_data'),
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_organizations_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'org_name', 'picture', 'added'),
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
                bx_import('BxDolConnection');
                $oConnection = isset($aParams['object']) ? BxDolConnection::getObjectInstance($aParams['object']) : false;
                if ($oConnection && isset($aParams['profile']) && (int)$aParams['profile']) {

                    $oProfile = BxDolProfile::getInstance($aParams['profile']);

                    $sMethod = 'getConnectedContentAsCondition';
                    if (isset($aParams['type']) && $aParams['type'] == 'initiators')
                        $sMethod = 'getConnectedInitiatorsAsCondition';

                    if (isset($aParams['type']) && $aParams['type'] == 'common') {

                        $a = $oConnection->getCommonContentAsCondition('id', (int)$aParams['profile'], (int)$aParams['profile2'], isset($aParams['mutual']) ? $aParams['mutual'] : false);
                        $oProfile2 = BxDolProfile::getInstance($aParams['profile2']);
                        if ($oProfile && $oProfile2)
                            $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());

                    } else {

                        $a = $oConnection->$sMethod('id', (int)$aParams['profile'], isset($aParams['mutual']) ? $aParams['mutual'] : false);
                        if ($oProfile)
                            $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_connections', $oProfile->getDisplayName());

                    }

                    $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
                    $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);
                    $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];

                }
                break;

            case 'recent':
                $this->aCurrent['rss']['link'] = 'modules/?r=orgs/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_orgs_page_title_browse_recent');
                $this->sBrowseUrl = 'page.php?i=organizations-home';
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

        parent::__construct();
    }

    function getAlterOrder() 
    {
        switch ($this->aCurrent['sorting']) {
        case 'none':
            return array();
        case 'last':
        default:
            $aSql = array();
            $aSql['order'] = " ORDER BY `bx_organizations_data`.`added` DESC";
            return $aSql;
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

