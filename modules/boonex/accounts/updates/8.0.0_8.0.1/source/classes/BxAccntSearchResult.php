<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAccntSearchResult extends BxBaseModGeneralSearchResult
{
    function __construct($sMode = '', $aParams = false)
    {
        parent::__construct($sMode, $aParams);

        $this->aCurrent =  array(
            'name' => 'bx_accounts',
            'object_metatags' => '',
            'title' => _t('_bx_accnt_page_title_browse'),
            'table' => 'sys_accounts',
            'tableSearch' => 'sys_accounts',
            'ownFields' => array(),
            'searchFields' => array('name', 'email'),
            'restriction' => array(
                'confirmed' => array('value' => '', 'field' => 'email_confirmed', 'operator' => '='),
        		'unconfirmed' => array('value' => '', 'field' => 'email_confirmed', 'operator' => '<>'),
            ),
            'join' => array (),
            'paginate' => array('perPage' => 20, 'start' => 0),
            'sorting' => 'none',
            'rss' => array(
                'title' => '',
                'link' => '',
                'image' => '',
                'profile' => 0,
                'fields' => array (
                    'Guid' => 'link',
                    'Link' => 'link',
                    'Title' => 'fullname',
                    'DateTimeUTS' => 'added',
                    'Desc' => 'fullname',
                    'Picture' => 'picture',
                ),
            ),
            'ident' => 'id'
        );

        $this->sFilterName = 'bx_accounts_filter';
        $this->oModule = $this->getMain();

        switch ($sMode) {
            case '': // search results
                $this->sBrowseUrl = BX_DOL_SEARCH_KEYWORD_PAGE;
                $this->aCurrent['title'] = _t('_bx_accnt');
                $this->aCurrent['paginate']['perPage'] = 5;
                unset($this->aCurrent['rss']);
                break;

            default:
                $this->isError = true;
        }

        parent::__construct();
    }
}

/** @} */
