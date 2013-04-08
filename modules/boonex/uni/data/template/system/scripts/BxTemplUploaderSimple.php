<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import ('BxBaseUploaderSimple');

/**
 * @see BxDolUploader
 */
class BxTemplUploaderSimple extends BxBaseUploaderSimple {
    function BxTemplUploaderSimple($aObject, $sStorageObject, $sUniqId) {
        parent::BxBaseUploaderSimple($aObject, $sStorageObject, $sUniqId);
    }
}

