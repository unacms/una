<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
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

    /**
     * Add conditions for private content
     */
    protected function addConditionsForPrivateContent($CNF, $oProfile, $aCustomGroup = array()) 
    {
        if(empty($CNF['OBJECT_PRIVACY_VIEW']))
            return;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        if(!$oPrivacy)
            return;

        $aCondition = $oPrivacy->getContentPublicAsCondition($oProfile ? $oProfile->id() : 0, $aCustomGroup);
        if(empty($aCondition) || !is_array($aCondition))
            return;

        if(isset($aCondition['restriction']))
            $this->aCurrent['restriction'] = array_merge($this->aCurrent['restriction'], $aCondition['restriction']);
        if(isset($aCondition['join']))
            $this->aCurrent['join'] = array_merge($this->aCurrent['join'], $aCondition['join']);

        $this->setProcessPrivateContent(false);
    }
}

/** @} */
