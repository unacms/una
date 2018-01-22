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
                $this->aCurrent['restriction']['album']['value'] = (int)$aParams['album_id'];
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . (int)$aParams['album_id']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_media_in_album');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode . '/' . (int)$aParams['album_id'];
                $this->aCurrent['sorting'] = 'order';
                $this->sOrderDirection = 'ASC';                
                break;

            case 'favorite':
                if(!$this->_updateCurrentForFavorite($sMode, array_merge($aParams, array('system' => $CNF['OBJECT_FAVORITES_MEDIA'])), $oProfileAuthor)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'recent':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_RECENT_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_recent_media');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode; 
                $this->aCurrent['sorting'] = 'last';
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_RECENT_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_featured_media');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss_media/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_popular_media');
                $this->aCurrent['rss']['link'] = BxDolPermalinks::getInstance()->permalink('modules/?r=albums/rss_media/' . $sMode);
                $this->aCurrent['sorting'] = 'popular';
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

        $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);

        $this->addContainerClass (array('bx-def-margin-sec-lefttopright-neg', 'bx-albums-medias-wrapper'));
    }

    function getAlterOrder()
    {
        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'order':
                $aSql['order'] = " ORDER BY `{$this->aCurrent['table']}`.`order` {$this->sOrderDirection}, `{$this->aCurrent['table']}`.`id` {$this->sOrderDirection} ";
                break;
            case 'last':
                $aSql['order'] = " ORDER BY `f`.`added` {$this->sOrderDirection}, `{$this->aCurrent['table']}`.`id` {$this->sOrderDirection} ";
                break;
            case 'featured':
                $aSql['order'] = " ORDER BY `{$this->aCurrent['table']}`.`featured` {$this->sOrderDirection}, `{$this->aCurrent['table']}`.`id` {$this->sOrderDirection}";
                break;
            case 'popular':
                $aSql['order'] = " ORDER BY `{$this->aCurrent['table']}`.`views` {$this->sOrderDirection}, `{$this->aCurrent['table']}`.`id` {$this->sOrderDirection}";
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

        switch ($this->aCurrent['sorting']) {
            case 'order':
                $this->sOrderDirection = $isNext ? 'ASC' : 'DESC';
                $sOper = $isNext ? '>' : '<';
                $this->aCurrent['restriction_sql'] = " AND (`{$this->aCurrent['table']}`.`order` {$sOper} {$aMediaInfo['order']} OR (`{$this->aCurrent['table']}`.`order` = {$aMediaInfo['order']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
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
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->oModule->_oConfig->CNF['URI_VIEW_MEDIA'] . '&id=' . $a['id']);
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
