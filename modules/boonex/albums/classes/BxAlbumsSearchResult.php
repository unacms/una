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

class BxAlbumsSearchResult extends BxBaseModTextSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        $this->aUnitViews = array('extended' => 'unit.html', 'gallery' => 'unit_gallery.html',);
    
        if (empty($aParams['unit_view']))
            $aParams['unit_view'] = 'extended';

        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_albums',
            'module_name' => 'bx_albums',
            'object_metatags' => 'bx_albums',
            'title' => _t('_bx_albums_page_title_browse'),
            'table' => 'bx_albums_albums',
            'ownFields' => array('id', 'title', 'text', 'thumb', 'author', 'added'),
            'searchFields' => array('title', 'text'),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
        		'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
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

            case 'author':
                $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
                if (!$oProfileAuthor) {
                    $this->isError = true;
                    break;
                }

                $this->aCurrent['restriction']['author']['value'] = $oProfileAuthor->id();

                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_by_author');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode . '/' . $oProfileAuthor->id();
                break;

            case 'public':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode;
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case 'updated':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_UPDATED']);
                $this->aCurrent['title'] = _t('_bx_albums_page_title_browse_updated');
                $this->aCurrent['rss']['link'] = 'modules/?r=albums/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'updated';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_albums');
                $this->aCurrent['paginate']['perPage'] = 3;
                unset($this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;
        }

        $this->processReplaceableMarkers($oProfileAuthor);

        $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
    }

    function displayResultBlock ()
    {
        $s = parent::displayResultBlock ();
        $s = '<div class="bx-albums-wrapper">' . $s . '</div>';
        return $this->oModule->_oTemplate->getJsCode('main') . $s;
    }

    function getAlterOrder()
    {
        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `bx_albums_albums`.`added` DESC';
                break;
            case 'updated':
                $aSql['order'] = ' ORDER BY `bx_albums_albums`.`changed` DESC';
                break;
            case 'popular':
                $aSql['order'] = ' ORDER BY `bx_albums_albums`.`views` DESC';
                break;
        }

        return $aSql;
    }

    function getDesignBoxMenu ()
    {
        return BxBaseModGeneralSearchResult::getDesignBoxMenu ();
    }
}

/** @} */
