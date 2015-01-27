<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModTextPageEntry');

/**
 * Entry create/edit pages
 */
class BxAlbumsPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _addJsCss()
    {
        $this->_oTemplate->addCss(array(
            BX_DOL_URL_PLUGINS_PUBLIC . 'photo-swipe/photoswipe.css',
            BX_DOL_URL_PLUGINS_PUBLIC . 'photo-swipe/default-skin/default-skin.css',
        ));

        $this->_oTemplate->addJs(array(
            'photo-swipe/photoswipe.min.js',
            'photo-swipe/photoswipe-ui-default.min.js',
        ));
    }
}

/** @} */
