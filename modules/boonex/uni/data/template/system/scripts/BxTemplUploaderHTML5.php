<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import ('BxBaseUploaderHTML5');

/**
 * @see BxDolUploader
 */
class BxTemplUploaderHTML5 extends BxBaseUploaderHTML5 {
    function BxTemplUploaderHTML5($aObject, $sStorageObject, $sUniqId) {
        parent::BxBaseUploaderHTML5($aObject, $sStorageObject, $sUniqId);
    }
}

