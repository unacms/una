<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
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
            'object_metatags' => 'bx_timeline',
            'title' => _t('_bx_timeline_page_title_browse'),
            'table' => 'bx_timeline_events',
            'ownFields' => array('id', 'owner_id', 'type', 'action', 'object_id', 'object_privacy_view', 'content', 'title', 'description', 'views', 'rate', 'votes', 'comments', 'reports', 'reposts', 'date', 'active', 'hidden', 'pinned'),
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
                unset($this->aCurrent['paginate']['perPage'], $this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;

        }

        $this->setProcessPrivateContent(false);
    }

    function displayResultBlock ()
    {
        $sResult = parent::displayResultBlock();
        if(empty($sResult))
            return $sResult;

        return $this->oModule->_oTemplate->getSearchBlock($sResult);
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
