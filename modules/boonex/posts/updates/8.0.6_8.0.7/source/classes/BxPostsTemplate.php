<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxPostsTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_posts';
        parent::__construct($oConfig, $oDb);
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        $s = parent::getAuthorAddon ($aData, $oProfile);
        if (!$aData['cat'])
            return $s;

        if (!($oCat = BxTemplCategory::getObjectInstance('bx_posts_cats')))
            return $s;

        if (!($aCats = BxDolForm::getDataItems('bx_posts_cats')) || !isset($aCats[$aData['cat']]))
            return $s;

        $s = _t('_bx_posts_txt_category_link', $oCat->getCategoryUrl($aData['cat']), $aCats[$aData['cat']]) . '<br />' . $s;

        return $s;
    }
}

/** @} */
