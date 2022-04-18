<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

class BxClssSearchResult extends BxBaseModTextSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_classes',
            'module_name' => 'bx_classes',
            'object_metatags' => 'bx_classes',
            'title' => _t('_bx_classes_page_title_browse'),
            'table' => 'bx_classes_classes',
            'ownFields' => array('id', 'title', 'text', 'thumb', 'author', 'added', 'avail', 'allow_view_to', 'start_date', 'end_date'),
            'searchFields' => array(),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
        		'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>'),
        		'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
        		'statusAdmin' => array('value' => 'active', 'field' => 'status_admin', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_classes_per_page_browse'), 'start' => 0),
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

        $this->sFilterName = 'bx_classes_filter';
        $this->oModule = $this->getMain();

        $CNF = &$this->oModule->_oConfig->CNF;

        $sSearchFields = getParam($CNF['PARAM_SEARCHABLE_FIELDS']);
        $this->aCurrent['searchFields'] = !empty($sSearchFields) ? explode(',', $sSearchFields) : '';

        $oProfileAuthor = null;

        switch ($sMode) {
            case 'author':
                if(!$this->_updateCurrentForAuthor($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;
                break;

            case 'next_in_context':
                $aParams['per_page'] = 1;

                $this->aCurrent['sorting'] = 'order';
                $this->aCurrent['restriction']['next_in_context1'] = array(
                    'field' => $CNF['FIELD_START_DATE'],
                    'operator' => '<=',
                    'value' => time(),
                );
                $this->aCurrent['restriction']['next_in_context2'] = array(
                    'field' => $CNF['FIELD_END_DATE'],
                    'operator' => '>=',
                    'value' => time(),
                );
                $this->aCurrent['join']['modules'] = array(
                    'type' => 'INNER',
                    'table' => 'bx_classes_modules',
                    'mainField' => 'module_id',
                    'onField' => 'id',
                    'joinFields' => array(),
                );

                $aParams['context'] = $aParams['next_in_context'];
                if(!$this->_updateCurrentForContext($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;

                break;

            case 'context':
                if(!$this->_updateCurrentForContext($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;
                break;
                
            case 'favorite':
                if(!$this->_updateCurrentForFavorite($sMode, $aParams, $oProfileAuthor))
                    $this->isError = true;
                break;

            case 'public':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_classes_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=classes/rss/' . $sMode;
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_classes_page_title_browse_featured');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=classes/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_TOP']);
                $this->aCurrent['title'] = _t('_bx_classes_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=classes/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case 'top':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_classes_page_title_browse_top');
                $this->aCurrent['rss']['link'] = 'modules/?r=classes/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'top';
                break;

            case 'updated':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_UPDATED']);
                $this->aCurrent['title'] = _t('_bx_classes_page_title_browse_updated');
                $this->aCurrent['rss']['link'] = 'modules/?r=classes/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'updated';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_classes');
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

    function getAlterOrder()
    {
        $aSql = parent::getAlterOrder();
        if ('order' == $this->aCurrent['sorting']) {
            $aSql['order'] = ' ORDER BY `bx_classes_modules`.`order`*1000000 + `bx_classes_classes`.`order` ASC';
        }
        return $aSql;
    }

}

/** @} */
