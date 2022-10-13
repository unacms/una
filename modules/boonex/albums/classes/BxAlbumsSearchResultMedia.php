<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAlbumsSearchResultMedia extends BxBaseModTextSearchResult
{
    protected $sOrderDirection = 'DESC';

    function __construct($sMode = '', $aParams = array())
    {
        $this->sUnitTemplateLiveSearch = 'unit_media_live_search.html';

        $aParams['unit_views'] = array('gallery' => 'unit_media.html', 'showcase' => 'unit_showcase.html');
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_albums_media',
            'module_name' => 'bx_albums',
            'object_metatags' => 'bx_albums_media',
            'title' => _t('_bx_albums_media'),
            'table' => 'bx_albums_files2albums',
            'ownFields' => array('id', 'title', 'data', 'content_id', 'file_id', 'order', 'views'),
            'searchFields' => array('title'),
            'restriction_sql' => '',
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
                'album' => array('value' => '', 'field' => 'content_id', 'operator' => '='),
                'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>'),
            ),
            'join' => array(
                'albums' => array(
                    'type' => 'INNER',
                    'table' => 'bx_albums_albums',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array(),
                ),
                'files' => array(
                    'type' => 'INNER',
                    'table' => 'bx_albums_files',
                    'table_alias' => 'f',
                    'mainField' => 'file_id',
                    'onField' => 'id',
                    'joinFields' => array('added'),
                ),
            ),
            'paginate' => array('perPage' => getParam('bx_albums_per_page_browse'), 'start' => 0),
            'sorting' => 'last',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'title',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'title',
                ),
            ),
            'ident' => 'id',
        );

        $this->sFilterName = 'bx_albums_filter';
        $this->oModule = $this->getMain();

        $oProfileAuthor = isset($aParams['author']) ? BxDolProfile::getInstance((int)$aParams['author']) : null;

        $CNF = &$this->oModule->_oConfig->CNF;

        switch ($sMode) {
            case 'album':
                $iAlbumId = (int)$aParams['album_id'];
                $this->aCurrent['restriction']['album']['value'] = $iAlbumId;
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iAlbumId);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_media_in_album');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode . '/' . $iAlbumId;
                $this->aCurrent['sorting'] = 'order';
                $this->sOrderDirection = 'ASC';
                $this->setProcessPrivateContent(true);

                if($CNF['PARAM_ORDER_BY_GHOSTS'])
                    $this->_updateCurrentForOrderByGhosts();
                break;

            case 'favorite':
                if(!$this->_updateCurrentForFavorite($sMode, array_merge($aParams, array('system' => $CNF['OBJECT_FAVORITES_MEDIA'])), $oProfileAuthor)) {
                    $this->isError = true;
                    break;
                }
                $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
                break;

            case 'recent':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_RECENT_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_recent_media');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode; 
                $this->aCurrent['sorting'] = 'last';
                $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_RECENT_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_featured_media');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_popular_media');
                $this->aCurrent['rss']['link'] = BxDolPermalinks::getInstance()->permalink('modules/?r=albums/rss_media/' . $sMode);
                $this->aCurrent['sorting'] = 'popular';
                $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
                break;

            case 'top':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_TOP_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_top_media');
                $this->aCurrent['rss']['link'] = BxDolPermalinks::getInstance()->permalink('modules/?r=albums/rss_media/' . $sMode);
                $this->aCurrent['sorting'] = 'top';
                $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                unset($this->aCurrent['paginate']['perPage'], $this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;
        }

        $this->sBrowseUrl = $this->_replaceMarkers($this->sBrowseUrl);
        $this->aCurrent['title'] = $this->_replaceMarkers($this->aCurrent['title']); 

        $this->removeContainerClass('bx-def-margin-bottom-neg');
        $this->addContainerClass(array('bx-def-margin-sec-lefttopright-neg', 'bx-def-margin-sec-bottom-neg', 'bx-albums-medias-wrapper'));

        $this->addCustomConditions($CNF, $oProfileAuthor, $sMode, $aParams);
    }

    function getAlterOrder()
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $sTable = $this->aCurrent['table'];
        $sWay = $this->sOrderDirection;

        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'order':
                $aSql['order'] = " ORDER BY `" . $sTable . "`.`order` " . $sWay . ", `" . $sTable . "`.`id` " . $sWay . " ";
                break;

            case 'order_by_ghosts':
                $aSql['order'] = " ORDER BY `g`.`order` " . $sWay . ", `" . $sTable . "`.`id` " . $sWay . " ";
                break;

            case 'last':
                $aSql['order'] = " ORDER BY `f`.`added` " . $sWay . ", `" . $sTable . "`.`id` " . $sWay . " ";
                break;

            case 'featured':
                $aSql['order'] = " ORDER BY `" . $sTable . "`.`featured` " . $sWay . ", `" . $sTable . "`.`id` " . $sWay . "";
                break;

            case 'popular':
                $aSql['order'] = " ORDER BY `" . $sTable . "`.`views` " . $sWay . ", `" . $sTable . "`.`id` " . $sWay . "";
                break;

            case 'top':
                $aSql['order'] = '';

                $aPartsUp = $aPartsDown = array();
                if(!empty($CNF['OBJECT_VOTES_MEDIA']) && ($oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_MEDIA'], 0, false)) !== false) {
                    $aVote = $oVote->getSystemInfo();
                    if(!empty($aVote['trigger_table']) && !empty($aVote['trigger_field_count']))
                        $aPartsUp[] = '`' . $aVote['trigger_table'] . '`.`' . $aVote['trigger_field_count'] . '`';
                }

                if(!empty($CNF['OBJECT_SCORES_MEDIA']) && ($oScore = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES_MEDIA'], 0, false)) !== false) {
                    $aScore = $oScore->getSystemInfo();
                    if(!empty($aScore['trigger_table']) && !empty($aScore['trigger_field_cup']) && !empty($aScore['trigger_field_cdown'])) {
                        $aPartsUp[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cup'] . '`';
                        $aPartsDown[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cdown'] . '`';
                    }
                }

                if(empty($aPartsUp) && empty($aPartsDown))
                    break;

                $aSql['order'] = ' ORDER BY ' . pow(10, 8) . ' * ((' . implode(' + ', $aPartsUp) . ') - (' . implode(' + ', $aPartsDown) . ')) / (UNIX_TIMESTAMP() - `f`.`added`) ' . $sWay;
                break;
        }

        return $aSql;
    }

    public function getNextPrevItem($aMediaInfo, $isNext)
    {
        $sOper = $isNext ? '<' : '>';

        $this->sOrderDirection = $isNext ? 'DESC' : 'ASC';
        $this->aCurrent['paginate']['perPage'] = 1;
        $this->aCurrent['restriction']['next_prev2'] = array('value' => $aMediaInfo['id'], 'field' => 'id', 'operator' => '!=');

        $this->setProcessPrivateContent(true);
        $aRestrictions = array_keys($this->aCurrent['restriction']);
        foreach ($aRestrictions as $sKey) 
            if (0 === strpos($sKey, 'privacy_'))
                unset($this->aCurrent['restriction'][$sKey]);

        switch ($this->aCurrent['sorting']) {
            case 'order':
                $this->sOrderDirection = $isNext ? 'ASC' : 'DESC';
                $sOper = $isNext ? '>' : '<';
                $this->aCurrent['restriction_sql'] = " AND (`{$this->aCurrent['table']}`.`order` {$sOper} {$aMediaInfo['order']} OR (`{$this->aCurrent['table']}`.`order` = {$aMediaInfo['order']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
            case 'order_by_ghosts':
                $this->sOrderDirection = $isNext ? 'ASC' : 'DESC';
                $sOper = $isNext ? '>' : '<';
                $this->aCurrent['restriction_sql'] = " AND (`g`.`order` {$sOper} {$aMediaInfo['gorder']} OR (`g`.`order` = {$aMediaInfo['gorder']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
            case 'last':
                $this->aCurrent['restriction_sql'] = " AND (`f`.`added` {$sOper} {$aMediaInfo['added']} OR (`f`.`added` = {$aMediaInfo['added']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
            case 'featured':
                $this->aCurrent['restriction_sql'] = " AND (`{$this->aCurrent['table']}`.`featured` {$sOper} {$aMediaInfo['featured']} OR (`{$this->aCurrent['table']}`.`featured` = {$aMediaInfo['featured']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
            case 'popular':
                $this->aCurrent['restriction_sql'] = " AND (`{$this->aCurrent['table']}`.`views` {$sOper} {$aMediaInfo['views']} OR (`{$this->aCurrent['table']}`.`views` = {$aMediaInfo['views']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
        }

        $aData = $this->getSearchData();
        if (count($aData) > 0)
            return array_shift($aData);

        return false;
    }

    function getRssUnitLink (&$a)
    {
        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->oModule->_oConfig->CNF['URI_VIEW_MEDIA'] . '&id=' . $a['id']));
    }

    protected function _updateCurrentForOrderByGhosts()
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $this->aCurrent['join']['ghosts'] = [
            'type' => 'INNER',
            'table' => 'sys_storage_ghosts',
            'table_alias' => 'g',
            'mainField' => 'file_id',
            'on_sql' => $this->oModule->_oDb->prepareAsString(" `g`.`id`=`bx_albums_files2albums`.`file_id` AND `g`.`content_id`=`bx_albums_files2albums`.`content_id` AND `g`.`object`=? ", $CNF['OBJECT_STORAGE']),
            'joinFields' => array('order'),
        ];
        $this->aCurrent['sorting'] = 'order_by_ghosts';
        $this->sOrderDirection = 'ASC';
    }

/*
    function displaySearchUnit ($aData)
    {
        $oMain = $this->getMain();
        return $oMain->_oTemplate->unit($aData, $this->bProcessPrivateContent, $this->_bLiveSearch ? $this->sUnitTemplateLiveSearch : $this->sUnitTemplate, $this->aUnitParams);
    }
*/
}

/** @} */
