<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import("BxBasePaginate");

/**
 * @see BxDolPaginate
 */
class BxTemplPaginate extends BxBasePaginate {

    function BxTemplPaginate($aParams, $oTemplate = false) {
        parent::BxBasePaginate($aParams, $oTemplate);
    }
}