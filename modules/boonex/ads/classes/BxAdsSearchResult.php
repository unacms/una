<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAdsSearchResult extends BxBaseModTextSearchResult
{
    protected $sModule;

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->sModule = 'bx_ads';
        $this->oModule = BxDolModule::getInstance($this->sModule);

        $CNF = &$this->oModule->_oConfig->CNF;

        $this->aCurrent = array(
            'name' => $this->sModule,
            'module_name' => $this->sModule,
            'object_metatags' => $CNF['OBJECT_METATAGS'],
            'title' => _t('_bx_ads_page_title_browse'),
            'table' => $CNF['TABLE_ENTRIES'],
            'ownFields' => array('id', 'title', 'text', 'thumb', 'author', 'added', 'price', 'quantity', 'notes_purchased'),
            'searchFields' => array(),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
                'featured' => array('value' => '', 'field' => 'featured', 'operator' => '<>'),
                'category' => array('value' => '', 'field' => 'category', 'operator' => '='),
                'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
                'statusAdmin' => array('value' => 'active', 'field' => 'status_admin', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_ads_per_page_browse'), 'start' => 0),
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

        $this->sFilterName = 'bx_ads_filter';

        $sSearchFields = getParam($CNF['PARAM_SEARCHABLE_FIELDS']);
        $this->aCurrent['searchFields'] = !empty($sSearchFields) ? explode(',', $sSearchFields) : '';

        $oProfileAuthor = null;

        switch ($sMode) {
            case 'author':
                if(!$this->_updateCurrentForAuthor($sMode, $aParams, $oProfileAuthor))
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
                $this->aCurrent['title'] = _t('_bx_ads_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=ads/rss/' . $sMode;
                break;

            case 'featured':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_ads_page_title_browse_featured');
                $this->aCurrent['restriction']['featured']['value'] = '0';
                $this->aCurrent['rss']['link'] = 'modules/?r=ads/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'featured';
                break;

            case 'popular':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_ads_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=ads/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case 'updated':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_UPDATED']);
                $this->aCurrent['title'] = _t('_bx_ads_page_title_browse_updated');
                $this->aCurrent['rss']['link'] = 'modules/?r=ads/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'updated';
                break;

            case 'category':
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_CATEGORIES']);
                if(!empty($aParams['category']))
                    $this->aCurrent['restriction']['category']['value'] = $aParams['category'];
                $this->aCurrent['title'] = _t('_bx_ads_page_title_browse_category');
                $this->aCurrent['rss']['link'] = 'modules/?r=ads/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'last';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_ads');
                unset($this->aCurrent['paginate']['perPage'], $this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;
        }

        $this->processReplaceableMarkers($oProfileAuthor);

        $this->addConditionsForPrivateContent($CNF, $oProfileAuthor);
        if($this->oModule->_oConfig->isPromotion())
            $this->addConditionsForPromotion($CNF, $oProfileAuthor, $sMode, $aParams);

        $this->addCustomConditions($CNF, $oProfileAuthor, $sMode, $aParams);        
    }

    function displayResultBlock()
    {
        $this->oModule->_oTemplate->addJs(['main.js']);

        return $this->oModule->_oTemplate->getJsCode('main') . parent::displayResultBlock();
    }

    function getAlterOrder()
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `' . $CNF['TABLE_ENTRIES'] . '`.`added` DESC';
                break;
            case 'featured':
                $aSql['order'] = ' ORDER BY `' . $CNF['TABLE_ENTRIES'] . '`.`featured` DESC';
                break;
            case 'updated':
                $aSql['order'] = ' ORDER BY `' . $CNF['TABLE_ENTRIES'] . '`.`changed` DESC';
                break;
            case 'popular':
                $aSql['order'] = ' ORDER BY `' . $CNF['TABLE_ENTRIES'] . '`.`views` DESC';
                break;
        }
        return $aSql;
    }

    protected function addConditionsForPromotion($CNF, $oProfile, $sMode, $aParams)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        $iDay = $this->oModule->_oConfig->getDay();
        $fPromotionCpm = $this->oModule->_oConfig->getPromotionCpm();

        if(!isset($this->aCurrent['join']))
            $this->aCurrent['join'] = [];

        $this->aCurrent['join'][] = [
            'type' => 'INNER',
            'table' => $CNF['TABLE_PROMO_TRACKER'],
            'mainField' => $CNF['FIELD_ID'],
            'on_sql' => $this->oModule->_oDb->prepareAsString("`{$CNF['TABLE_PROMO_TRACKER']}`.`entry_id`=`{$this->aCurrent['table']}`.`{$CNF['FIELD_ID']}` AND `{$CNF['TABLE_PROMO_TRACKER']}`.`date`=?", $iDay),
            'joinFields' => ['impressions', 'clicks'],
        ];

        $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], [
            'budget_total' => ['value' => 0, 'field' => 'budget_total', 'operator' => '<>'],
        ]);

        $this->aCurrent['restriction_sql'] = "";
        $this->aCurrent['restriction_sql'] .= " AND `{$this->aCurrent['table']}`.`{$CNF['FIELD_BUDGET_TOTAL']}` > ({$fPromotionCpm} * `{$this->aCurrent['table']}`.`impressions`)/1000";
        $this->aCurrent['restriction_sql'] .= " AND `{$this->aCurrent['table']}`.`{$CNF['FIELD_BUDGET_DAILY']}` > ({$fPromotionCpm} * `{$CNF['TABLE_PROMO_TRACKER']}`.`impressions`)/1000";
    }
}

/** @} */
