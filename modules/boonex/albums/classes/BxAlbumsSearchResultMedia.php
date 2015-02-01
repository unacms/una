<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModTextSearchResult');

class BxAlbumsSearchResultMedia extends BxBaseModTextSearchResult
{
    protected $sOrderDirection = 'DESC';

    function __construct($sMode = '', $aParams = array())
    {
        $this->aUnitViews = array('gallery' => 'unit_media.html');
    
        if (empty($aParams['unit_view']))
            $aParams['unit_view'] = 'gallery';

        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_albums',
            'object_metatags' => 'bx_albums',
            'title' => _t('_bx_albums_page_title_browse'),
            'table' => 'bx_albums_files2albums',
            'ownFields' => array('id', 'title', 'data', 'content_id', 'file_id', 'order'),
            'searchFields' => array('title'),
            'restriction_sql' => '',
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
                'album' => array('value' => '', 'field' => 'content_id', 'operator' => '='),
            ),
            'join' => array(
                'albums' => array(
                    'type' => 'INNER',
                    'table' => 'bx_albums_albums',
                    'mainField' => 'content_id',
                    'onField' => 'id',
                    'joinFields' => array('views'),
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
                    'Desc' => 'text',
                ),
            ),
            'ident' => 'id',
        );

        $this->sFilterName = 'bx_albums_filter';
        $this->oModule = $this->getMain();

        $oProfileAuthor = null;

        $CNF = &$this->oModule->_oConfig->CNF;

        switch ($sMode) {
            case 'album':
                $this->aCurrent['restriction']['album']['value'] = $aParams['album_id'];
                //$this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
                //$this->aCurrent['title'] = _t('_bx_albums_page_title_browse_media_in_album');
                //$this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode . '/' . $oProfileAuthor->id();
                $this->aCurrent['sorting'] = 'order';
                $this->sOrderDirection = 'ASC';
                break;

            case 'popular':
                bx_import('BxDolPermalinks');
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR_MEDIA']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_popular_media');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode; // TODO: refer to another search class in 'rss' method for this rss feed
                $this->aCurrent['sorting'] = 'popular';
                break;
/* TODO: add to search
            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_albums');
                $this->aCurrent['paginate']['perPage'] = 3;
                unset($this->aCurrent['rss']);
                break;
*/
            default:
                $sMode = '';
                $this->isError = true;
        }
/*
        // add replaceable markers and replace them
        if ($oProfileAuthor) {
            $this->addMarkers($oProfileAuthor->getInfo()); // profile info is replaceable
            $this->addMarkers(array('profile_id' => $oProfileAuthor->id())); // profile id is replaceable
            $this->addMarkers(array('display_name' => $oProfileAuthor->getDisplayName())); // profile display name is replaceable
        }
*/
        $this->sBrowseUrl = $this->_replaceMarkers($this->sBrowseUrl);
        $this->aCurrent['title'] = $this->_replaceMarkers($this->aCurrent['title']);

        // add conditions for private content
        bx_import('BxDolPrivacy');
        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        $a = $oPrivacy ? $oPrivacy->getContentPublicAsCondition($oProfileAuthor ? $oProfileAuthor->id() : 0) : array();
        if (isset($a['restriction']))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $a['restriction']);
        if (isset($a['join']))
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $a['join']);

        $this->setProcessPrivateContent(false);
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
            case 'popular':
                $aSql['order'] = " ORDER BY `bx_albums_albums`.`views` {$this->sOrderDirection}, `{$this->aCurrent['table']}`.`id` {$this->sOrderDirection}";
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
            case 'popular':
                $this->aCurrent['restriction_sql'] = " AND (`bx_albums_albums`.`views` {$sOper} {$aMediaInfo['views']} OR (`bx_albums_albums`.`views` = {$aMediaInfo['views']} AND `{$this->aCurrent['table']}`.`id` {$sOper} {$aMediaInfo['id']})) ";
                break;
        }

        $aData = $this->getSearchData();
        if (count($aData) > 0)
            return array_shift($aData);

        return false;
    }
}

/** @} */
