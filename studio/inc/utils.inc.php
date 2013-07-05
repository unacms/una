<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');


/*
 * Returns unique SID for communication with BoonEx Unity.
 * @see BxDolStudioStore  -> checkoutCart and BxDolStudioOAuth -> authorize
 */
function generateSid() {
	return md5(BX_DOL_URL_ROOT . '_' . BX_DOL_VERSION . '.' . BX_DOL_BUILD);
}
/** @} */