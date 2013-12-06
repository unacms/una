<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxBaseVote');

/**
 * @see BxDolVote
 */
class BxTemplVote extends BxBaseVote
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
		parent::__construct($sSystem, $iId, $iInit);
    }
}

