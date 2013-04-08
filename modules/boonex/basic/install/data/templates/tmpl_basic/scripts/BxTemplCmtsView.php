<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxBaseCmtsView');

/**
 * @see BxDolCmts
 */
class BxTemplCmtsView extends BxBaseCmtsView {

    function BxTemplCmtsView( $sSystem, $iId, $iInit = 1 ) {
        BxBaseCmtsView::BxBaseCmtsView( $sSystem, $iId, $iInit );
    }
}

