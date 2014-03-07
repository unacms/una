<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplSearchResult');

class BxBaseModTextSearchResult extends BxTemplSearchResult 
{
    protected $oModule;
    protected $aUnitViews = array('extended' => 'unit.html', 'gallery' => 'unit_gallery.html');
    protected $sUnitViewDefault = 'gallery';
    protected $sUnitViewParamName = 'unit_view';

    function __construct($sMode = '', $aParams = array()) 
    {
        parent::__construct();

        $this->aGetParams = array($this->sUnitViewParamName);
        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];
        if (isset($this->aUnitViews[bx_get($this->sUnitViewParamName)]))
            $this->sUnitTemplate = $this->aUnitViews[bx_get($this->sUnitViewParamName)];
    }

    function getMain() 
    {
        return BxDolModule::getInstance($this->aCurrent['name']);
    }

    function getDesignBoxMenu () 
    {
        $aMenu = parent::getDesignBoxMenu ();

        return array_merge(
            array(
                array('name' => 'gallery', 'title' => _t('_sys_menu_title_gallery'), 'link' => $this->getCurrentUrl(array($this->sUnitViewParamName => 'gallery')), 'icon' => 'th'),
                array('name' => 'extended', 'title' => _t('_sys_menu_title_extended'), 'link' => $this->getCurrentUrl(array($this->sUnitViewParamName => 'extended')), 'icon' => 'list'),
            ),
            $aMenu
        );
    }

    function getRssUnitLink (&$a) 
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $a[$CNF['FIELD_ID']]);
    }

    function getRssPageUrl () 
    {
        if (false === parent::getRssPageUrl())
            return false;

        bx_import('BxDolPermalinks');
        $oPermalinks = BxDolPermalinks::getInstance();
        return BX_DOL_URL_ROOT . $oPermalinks->permalink($this->aCurrent['rss']['link']);
    }

    function rss () 
    {        
        if (!isset($this->aCurrent['rss']))
            return '';

        $this->aCurrent['paginate']['perPage'] = getParam($this->oModule->_oConfig->CNF['PARAM_NUM_RSS']);
        return parent::rss();
    }

    function _getPseud () 
    {
        return array(
            'id' => 'id',
            'title' => 'title',
            'text' => 'text',
            'added' => 'added',
            'author' => 'author',
            'photo' => 'photo',
        );
    }
}

/** @} */

