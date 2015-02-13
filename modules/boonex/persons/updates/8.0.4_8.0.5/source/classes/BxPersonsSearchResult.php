<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

class BxPersonsSearchResult extends BxBaseModProfileSearchResult
{
    function __construct($sMode = '', $aParams = false)
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent =  array(
            'name' => 'bx_persons',
            'object_metatags' => 'bx_persons',
            'title' => _t('_bx_persons_page_title_browse'),
            'table' => 'sys_profiles',
            'tableSearch' => 'bx_persons_data',
            'ownFields' => array(),
            'searchFields' => array('fullname', 'description'),
            'restriction' => array(
        		'account_id' => array('value' => '', 'field' => 'account_id', 'operator' => '='),
                'perofileStatus' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'perofileType' => array('value' => 'bx_persons', 'field' => 'type', 'operator' => '='),
                'owner' => array('value' => '', 'field' => 'author', 'operator' => '=', 'table' => 'bx_persons_data'),
            ),
            'join' => array (
                'profile' => array(
                    'type' => 'INNER',
                    'table' => 'bx_persons_data',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('id', 'fullname', 'picture', 'added'),
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
                    'Title' => 'fullname',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'fullname',
                    'Picture' => 'picture',
                ),
            ),
            'ident' => 'id'
        );

        $this->sFilterName = 'bx_persons_filter';
        $this->oModule = $this->getMain();

        switch ($sMode) {

            case 'connections':
                if ($this->_setConnectionsConditions($aParams)) {
                    $oProfile = BxDolProfile::getInstance($aParams['profile']);
                    $oProfile2 = isset($aParams['profile2']) ? BxDolProfile::getInstance($aParams['profile2']) : null;

                    if (isset($aParams['type']) && $aParams['type'] == 'common' && $oProfile && $oProfile2)
                        $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_connections_mutual', $oProfile->getDisplayName(), $oProfile2->getDisplayName());
                    elseif ((!isset($aParams['type']) || $aParams['type'] != 'common') && $oProfile)
                        $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_connections', $oProfile->getDisplayName());

                    $this->aCurrent['rss']['link'] = 'modules/?r=persons/rss/' . $sMode . '/' . $aParams['object'] . '/' . $aParams['type'] . '/' . (int)$aParams['profile'] . '/' . (int)$aParams['profile2'] . '/' . (int)$aParams['mutual'];
                }
                break;

            case 'recent':
                $this->aCurrent['rss']['link'] = 'modules/?r=persons/rss/' . $sMode;
                $this->aCurrent['title'] = _t('_bx_persons_page_title_browse_recent');
                $this->sBrowseUrl = 'page.php?i=persons-home';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_persons');
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
            $aSql['order'] = " ORDER BY `bx_persons_data`.`added` DESC";
            return $aSql;
        }
    }

    function _getPseud ()
    {
        return array(
            'id' => 'id',
            'fullname' => 'fullname',
            'added' => 'added',
            'author' => 'author',
            'picture' => 'picture',
        );
    }
}

/** @} */
