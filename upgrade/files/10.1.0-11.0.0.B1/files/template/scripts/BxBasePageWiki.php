<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Wiki page
 */
class BxBasePageWiki extends BxTemplPage
{
    public function __construct($aObject, $oTemplate)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getPageCodeVars ()
    {
        $aVars = parent::_getPageCodeVars ();
        
        $oWiki = BxDolWiki::getObjectInstance($this->_aObject['module']);
        if ($oWiki && $oWiki->isAllowed('add')) {
            foreach ($aVars as $sKey => $sCell) {
                if (0 !== strncmp('cell_', $sKey, 5))
                    continue;
                $aVars[$sKey] = $sCell . bx_srv('system', 'wiki_add_block', array($oWiki, $this->_sObject, $sKey), 'TemplServiceWiki');
            }
        }
        return $aVars;
    }
}

/** @} */
