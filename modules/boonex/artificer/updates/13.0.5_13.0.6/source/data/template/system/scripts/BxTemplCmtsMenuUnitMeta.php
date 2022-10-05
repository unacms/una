<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplCmtsMenuUnitMeta extends BxBaseCmtsMenuUnitMeta
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
    }
    
    protected function _getMenuItemAuthor($aItem)
    {
        list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit, $sAuthorBadges) = $this->_oCmts->getAuthorInfo($this->_aCmt['cmt_author_id']);
    
        $sResult = '';
        if(!empty($sAuthorLink))
            $sResult = $this->getUnitMetaItemLink($sAuthorName, array(
                'href' => $sAuthorLink,
                'class' => $this->_sStylePrefix . '-username bx-def-font-author whitespace-nowrap hover:underline',
                'title' => bx_html_attribute($sAuthorName),
            )). $sAuthorBadges;
        else
            $sResult = $this->getUnitMetaItemText($sAuthorName, array(
                'class' => $this->_sStylePrefix . '-username bx-def-font-author whitespace-nowrap'
            )). $sAuthorBadges;

        return $sResult;
    }
}

/** @} */
