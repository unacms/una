<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPhotosSearchResult extends BxBaseModTextSearchResult
{
    public $aParams;

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->aParams = $aParams;
        $this->aCurrent = array(
            'name' => 'bx_photos',
            'module_name' => 'bx_photos',
            'object_metatags' => 'bx_photos',
            'title' => _t('_bx_photos_page_title_browse'),
            'table' => 'bx_photos_entries',
            'ownFields' => array('id', 'title', 'text', 'thumb', 'author', 'added', 'views'),
            'searchFields' => array(),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
                'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>'),
                'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'statusAdmin' => array('value' => 'active', 'field' => 'status_admin', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_photos_per_page_browse'), 'start' => 0),
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
            		'Image' => 'thumb'
                ),
            ),
            'ident' => 'id',
        );

        $this->sFilterName = 'bx_photos_filter';
        $this->oModule = $this->getMain();

        $CNF = &$this->oModule->_oConfig->CNF;

        $sSearchFields = getParam($CNF['PARAM_SEARCHABLE_FIELDS']);
        $this->aCurrent['searchFields'] = !empty($sSearchFields) ? explode(',', $sSearchFields) : '';

        $oProfileAuthor = null;

        switch ($sMode) {
            case 'author':
                if(empty($this->aParams['author']) && bx_get('author') !== false)
                    $this->aParams['author'] = bx_process_input(bx_get('author'));

                if(!$this->_updateCurrentForAuthor($sMode, $this->aParams, $oProfileAuthor))
                    $this->isError = true;
                break;

            case 'context':
                if(!$this->_updateCurrentForContext($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;
                break;
                
            case 'favorite':
                if(empty($this->aParams['author']) && bx_get('author') !== false)
                    $this->aParams['author'] = bx_process_input(bx_get('author'));

                if(!$this->_updateCurrentForFavorite($sMode, $this->aParams, $oProfileAuthor))
                    $this->isError = true;
                break;

            case 'public':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_photos_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=photos/rss/' . $sMode;
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_photos_page_title_browse_featured');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=photos/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_photos_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=photos/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case 'top':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_TOP']);
                $this->aCurrent['title'] = _t('_bx_photos_page_title_browse_top');
                $this->aCurrent['rss']['link'] = 'modules/?r=photos/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'top';
                break;

            case 'updated':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_UPDATED']);
                $this->aCurrent['title'] = _t('_bx_photos_page_title_browse_updated');
                $this->aCurrent['rss']['link'] = 'modules/?r=photos/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'updated';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_photos');
                unset($this->aCurrent['paginate']['perPage'], $this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;
        }

        $this->processReplaceableMarkers($oProfileAuthor);

        $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
        $this->addCustomConditions($CNF, $oProfileAuthor, $sMode, $aParams);
    }

    function getSearchData ()
    {
        $aResult = parent::getSearchData();
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

		if(!empty($this->aParams) && is_array($this->aParams))
        	$this->aUnitParams = array_merge($this->aUnitParams, $this->aParams);

        $this->aUnitParams['start'] = $this->aCurrent['paginate']['start'];
        $this->aUnitParams['per_page'] = $this->aCurrent['paginate']['perPage'];

        return $aResult;
    }
}

/** @} */
