<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxGlsrMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_glossary';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemEditGlossary($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteGlossary($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
