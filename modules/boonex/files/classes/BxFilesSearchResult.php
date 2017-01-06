<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesSearchResult extends BxBaseModTextSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_files',
            'module_name' => 'bx_files',
            'object_metatags' => 'bx_files',
            'title' => _t('_bx_files_page_title_browse'),
            'table' => 'bx_files_main',
            'ownFields' => array('id', 'title', 'desc', 'author', 'added'),
            'searchFields' => array(),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
        		'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_files_per_page_browse'), 'start' => 0),
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
                    'Desc' => 'desc',
                ),
            ),
            'ident' => 'id',
        );

        $this->sFilterName = 'bx_files_filter';
        $this->oModule = $this->getMain();
        $this->aCurrent['searchFields'] = explode(',', getParam($this->oModule->_oConfig->CNF['PARAM_SEARCHABLE_FIELDS']));

        $oProfileAuthor = null;

        $CNF = &$this->oModule->_oConfig->CNF;

        switch ($sMode) {
            case 'author':
                if(!$this->_updateCurrentForAuthor($sMode, $aParams, $oProfileAuthor)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'favorite':
                if(!$this->_updateCurrentForFavorite($sMode, $aParams, $oProfileAuthor)) {
                    $this->isError = true;
                    break;
                }
                break;

            case 'public':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_files_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=files/rss/' . $sMode;
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_files_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=files/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case 'updated':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_UPDATED']);
                $this->aCurrent['title'] = _t('_bx_files_page_title_browse_updated');
                $this->aCurrent['rss']['link'] = 'modules/?r=files/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'updated';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_files');
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

    function getAlterOrder()
    {
        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `bx_files_main`.`added` DESC';
                break;
            case 'updated':
                $aSql['order'] = ' ORDER BY `bx_files_main`.`changed` DESC';
                break;
            case 'popular':
                $aSql['order'] = ' ORDER BY `bx_files_main`.`views` DESC';
                break;
        }
        return $aSql;
    }
}

/** @} */
