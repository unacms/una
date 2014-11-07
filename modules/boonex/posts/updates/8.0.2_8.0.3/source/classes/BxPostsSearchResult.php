<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextSearchResult');

class BxPostsSearchResult extends BxBaseModTextSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent = array(
            'name' => 'bx_posts',
            'object_metatags' => 'bx_posts',
            'title' => _t('_bx_posts_page_title_browse'),
            'table' => 'bx_posts_posts',
            'ownFields' => array('id', 'title', 'text', 'thumb', 'author', 'added'),
            'searchFields' => array('title', 'text'),
            'restriction' => array(
                'author' => array('value' => '', 'field' => 'author', 'operator' => '='),
        		'status' => array('value' => 'active', 'field' => 'status', 'operator' => '='),
            ),
            'paginate' => array('perPage' => getParam('bx_posts_per_page_browse'), 'start' => 0),
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

        $this->sFilterName = 'bx_posts_filter';
        $this->oModule = $this->getMain();

        $oProfileAuthor = null;

        $CNF = &$this->oModule->_oConfig->CNF;

        switch ($sMode) {

            case 'author':
                bx_import('BxDolProfile');
                $oProfileAuthor = BxDolProfile::getInstance((int)$aParams['author']);
                if (!$oProfileAuthor) {
                    $this->isError = true;
                    break;
                }

                $this->aCurrent['restriction']['author']['value'] = $oProfileAuthor->id();

                $this->sBrowseUrl = 'page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id={profile_id}';
                $this->aCurrent['title'] = _t('_bx_posts_page_title_browse_by_author');
                $this->aCurrent['rss']['link'] = 'modules/?r=posts/rss/' . $sMode . '/' . $oProfileAuthor->id();
                break;

            case 'public':
                bx_import('BxDolPermalinks');
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_HOME']);
                $this->aCurrent['title'] = _t('_bx_posts_page_title_browse_recent');
                $this->aCurrent['rss']['link'] = 'modules/?r=posts/rss/' . $sMode;
                break;

            case 'popular':
                bx_import('BxDolPermalinks');
                $this->sBrowseUrl = BxDolPermalinks::getInstance()->permalink($CNF['URL_POPULAR']);
                $this->aCurrent['title'] = _t('_bx_posts_page_title_browse_popular');
                $this->aCurrent['rss']['link'] = 'modules/?r=posts/rss/' . $sMode;
                $this->aCurrent['sorting'] = 'popular';
                break;

            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_posts');
                $this->aCurrent['paginate']['perPage'] = 3;
                unset($this->aCurrent['rss']);
                break;

            default:
                $sMode = '';
                $this->isError = true;
        }

        // add replaceable markers and replace them
        if ($oProfileAuthor) {
            $this->addMarkers($oProfileAuthor->getInfo()); // profile info is replacable
            $this->addMarkers(array('profile_id' => $oProfileAuthor->id())); // profile id is replacable
            $this->addMarkers(array('display_name' => $oProfileAuthor->getDisplayName())); // profile display name is replacable
        }

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

    function displayResultBlock ()
    {
        $s = parent::displayResultBlock ();
        $s = '<div class="bx-posts-wrapper ' . ('unit_gallery.html' == $this->sUnitTemplate ? 'bx-def-margin-neg bx-clearfix' : '') . '">' . $s . '</div>';
        return $s;
    }

    function getAlterOrder()
    {
        $aSql = array();
        switch ($this->aCurrent['sorting']) {
            case 'last':
                $aSql['order'] = ' ORDER BY `bx_posts_posts`.`added` DESC';
                break;
            case 'popular':
                $aSql['order'] = ' ORDER BY `bx_posts_posts`.`views` DESC';
                break;
        }
        return $aSql;
    }
}

/** @} */
