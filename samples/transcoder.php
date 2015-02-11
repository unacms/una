<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section transcoder_images Transcoder Images
 *
 * This sample shows how transcoder can be used for the folder with images.
 *
 * @code
 *
 * CREATE TABLE IF NOT EXISTS `bx_resizer_files` (
 *   `id` int(11) NOT NULL AUTO_INCREMENT,
 *   `profile_id` int(10) unsigned NOT NULL,
 *   `remote_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *   `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *   `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 *   `mime_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
 *   `ext` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
 *   `size` int(11) NOT NULL,
 *   `added` int(11) NOT NULL,
 *   `modified` int(11) NOT NULL,
 *   `private` int(11) NOT NULL,
 *   PRIMARY KEY (`id`),
 *   UNIQUE KEY `remote_id` (`remote_id`)
 * );
 *
 * INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
 * ('resizer', 'Local', '', 360, 84000, 0, 'bx_resizer_files', 'deny-allow', '', 'exe,com,bat,pif,scr', 1000000000, 0, 100, 0, 300000000, 0);
 *
 * INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES
 * ('sample', 'resizer', 'Folder', 'a:1:{s:4:"path";s:12:"samples/img/";}', 'no', 0, 0, 0);
 *
 * INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
 * ('sample', 'Resize', 'a:3:{s:1:"w";i:100;s:1:"h";i:100;s:13:"square_resize";i:0;}', 1),
 * ('sample', 'Grayscale', '', 2);
 *
 * @endcode
 *
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample transcoder");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $iProfileId = 123;
    $oTranscoder = BxDolTranscoderImage::getObjectInstance('sample');
    if (!$oTranscoder)
        die('Transcoder object isn\'t defined');

    echo "registerHandlers: [" . $oTranscoder->registerHandlers() . "] <hr />\n";

    echo "
<script>
$(document).ready(function () {
    $('img').on('load', function () {
        $(this).after(' <span>size: <b>' + $(this).width() + 'x' + $(this).height() + '</b></span>')
    });
});
</script>
";

    $oStorage = $oTranscoder->getStorage();
    if (isset($_POST['delete'])) {
        foreach ($_POST['file_id'] as $iFileId) {
            $bRet = $oStorage->deleteFile($iFileId, $iProfileId);
            if ($bRet)
                echo "<hr />deleted file id: " . $iFileId . "<hr />";
            else
                echo "<hr />file deleting error: " . $oStorage->getErrorString() . "<hr />";
        }
    } else {
        $sPath = BX_DIRECTORY_PATH_ROOT . 'samples/img/';
        $h = opendir($sPath);
        while (false !== ($sFile = readdir($h))) {
            if ('.' == $sFile[0] || !is_file($sPath . $sFile))
                continue;
            $sUrl = $oTranscoder->getFileUrl($sFile);
            echo $sFile . ' : <img src="' . $sUrl . '" /> <br /> ' . $sUrl . ' <hr />';
        }
    }

    $a = $oStorage->getFilesAll();
    echo "<hr /> <h2>Files List:</h2>";
    echo '<form method="POST">';
    foreach ($a as $r)
        echo '<input type="checkbox" name="file_id[]" value="' . $r['id'] . '" /> ' . $r['file_name'] . '<br />';
    echo '<input type="submit" name="delete" value="Delete" />';
    echo '</form>';

    $s = ob_get_clean();
    return DesignBoxContent("Sample transcoder", $s, BX_DB_PADDING_DEF);

}
