<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     TridentModules
 *
 * @{
 */

class BxTimelineSearchResult extends BxBaseModNotificationsSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_timeline',
            'module_name' => 'bx_timeline',
            'title' => _t('_bx_timeline_page_title_browse'),
            'table' => 'bx_timeline_events',
            'ownFields' => array('id', 'owner_id', 'type', 'action', 'object_id', 'object_privacy_view', 'content', 'title', 'description', 'rate', 'votes', 'comments', 'reports', 'shares', 'date', 'active', 'hidden', 'pinned'),
            'searchFields' => array('description'),
            'restriction' => array(
        		'internal' => array('value' => 'timeline_common_post', 'field' => 'type', 'operator' => '='),
        		'active' => array('value' => '1', 'field' => 'active', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_timeline_events_per_page'), 'start' => 0),
            'sorting' => 'last',
            'ident' => 'id',
        );

        $this->sFilterName = 'bx_timeline_filter';
        $this->oModule = $this->getMain();

        switch ($sMode) {

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_timeline');
                $this->aCurrent['paginate']['perPage'] = 3;
                break;

            default:
                $sMode = '';
                $this->isError = true;

        }

        $this->setProcessPrivateContent(false);
    }

    function getAlterOrder()
    {
        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `bx_timeline_events`.`date` DESC';
                break;
        }
        return $aSql;
    }
}

/** @} */
