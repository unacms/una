<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModGeneralSearchResult extends BxTemplSearchResult
{
    protected $oModule;

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct();
    }

    function getMain()
    {
        return BxDolModule::getInstance($this->aCurrent['module_name']);
    }

    function getRssUnitLink (&$a)
    {
        $CNF = &$this->oModule->_oConfig->CNF;

        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $a[$CNF['FIELD_ID']]);
    }

    function getRssPageUrl ()
    {
        if (false === parent::getRssPageUrl())
            return false;

        $oPermalinks = BxDolPermalinks::getInstance();
        return BX_DOL_URL_ROOT . $oPermalinks->permalink($this->aCurrent['rss']['link']);
    }

    function rss ()
    {
        if (!isset($this->aCurrent['rss']))
            return '';

        $this->aCurrent['paginate']['perPage'] = empty($this->oModule->_oConfig->CNF['PARAM_NUM_RSS']) ? 10 : getParam($this->oModule->_oConfig->CNF['PARAM_NUM_RSS']);

        return parent::rss();
    }
}

/** @} */
