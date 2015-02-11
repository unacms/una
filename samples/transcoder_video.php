<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page samples
 * @section transcoder_videos Transcoder Videos
 *
 * This sample shows how transcoder can be used for video files.
 *
 * @code

CREATE TABLE `sample_transcoder_video_orig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ext` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sample_transcoder_video_orig', 'Local', '', 360, 2592000, 0, 'sample_transcoder_video_orig', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0);

CREATE TABLE `sample_transcoder_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ext` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sample_transcoder_video', 'Local', '', 360, 2592000, 0, 'sample_transcoder_video', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,jpg', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sample_video_poster', 'sample_transcoder_video', 'Storage', 'a:1:{s:6:"object";s:28:"sample_transcoder_video_orig";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('sample_video_mp4', 'sample_transcoder_video', 'Storage', 'a:1:{s:6:"object";s:28:"sample_transcoder_video_orig";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('sample_video_webm', 'sample_transcoder_video', 'Storage', 'a:1:{s:6:"object";s:28:"sample_transcoder_video_orig";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sample_video_webm', 'Webm', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:4:"webm";}', 0),
('sample_video_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"mp4";}', 0),
('sample_video_poster', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0);

 * @endcode
 *
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample video transcoder");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $sTranscoderObjectPoster = 'sample_video_poster';
    $sTranscoderObjectMP4 = 'sample_video_mp4';
    $sTranscoderObjectWebM = 'sample_video_webm';
    $sStorageObjectOrig = 'sample_transcoder_video_orig';
    $iProfileId = bx_get_logged_profile_id();

    if (!$iProfileId) {
        echo "You aren't logged in.";
        exit;
    }

    $iPrunedFiles = BxDolTranscoder::pruning();
    if ($iPrunedFiles) {
        echo "iPrunedFiles: $iPrunedFiles";
        exit;
    }
    $oTranscoderPoster = BxDolTranscoderVideo::getObjectInstance($sTranscoderObjectPoster);
    $oTranscoderMP4 = BxDolTranscoderVideo::getObjectInstance($sTranscoderObjectMP4);
    $oTranscoderWebM = BxDolTranscoderVideo::getObjectInstance($sTranscoderObjectWebM);
    if (!$oTranscoderPoster || !$oTranscoderMP4 || !$oTranscoderWebM) {
        echo "Transcoder object is not available: " . $sTranscoderObjectPoster . ', ' . $sTranscoderObjectMP4 . ', ' . $sTranscoderObjectWebM;
        exit;
    }
    echo "registerHandlers poster: [" . $oTranscoderPoster->registerHandlers() . "] <br />\n";
    echo "registerHandlers mp4: [" . $oTranscoderMP4->registerHandlers() . "] <br />\n";
    echo "registerHandlers webm: [" . $oTranscoderWebM->registerHandlers() . "] <hr class='bx-def-hr' />\n";

    $oStorageOrig = BxDolStorage::getObjectInstance($sStorageObjectOrig);
    if (!$oStorageOrig) {
        echo "Storage object is not available: " . $sStorageObjectOrig;
        exit;
    }

    if (isset($_POST['upload'])) {
    
        $iId = $oStorageOrig->storeFileFromForm($_FILES['file'], true, $iProfileId);
        if ($iId) {
            $iCount = $oStorageOrig->afterUploadCleanup($iId, $iProfileId);
            echo "<h2>Uploaded file id: " . $iId . "(deleted ghosts:" . $iCount . ") </h2>";

            // force transcode
            echo "Force transcode: <br />";
            echo "poster: " . $oTranscoderPoster->getFileUrl($iId) . '<br />';
            echo "mp4: " . $oTranscoderMP4->getFileUrl($iId) . '<br />';
            echo "webm: " . $oTranscoderWebM->getFileUrl($iId) . '<hr class="bx-def-hr" />';
        } else {
            echo "<h2>Error uploading file: " . $oStorage->getErrorString() . '</h2><hr class="bx-def-hr" />';
        }

    }
    elseif (isset($_POST['delete'])) {

        foreach ($_POST['file_id'] as $iFileId) {
            $bRet = $oStorageOrig->deleteFile($iFileId, $iProfileId);
            if ($bRet)
                echo "<h2>Deleted file id: " . $iFileId . '</h2><hr class="bx-def-hr" />';
            else
                echo "<h2>File deleting error: " . $oStorageOrig->getErrorString() . '</h2><hr class="bx-def-hr" />';
        }

    } 
    else {

        $a = $oStorageOrig->getFilesAll();
        foreach ($a as $r) {
            $sUrlPoster = $oTranscoderPoster->getFileUrl($r['id']);
            $sUrlMP4 = $oTranscoderMP4->getFileUrl($r['id']);
            $sUrlWebM = $oTranscoderWebM->getFileUrl($r['id']);

            echo '<h3>' . $r['file_name'] . '</h3>';
            echo BxTemplFunctions::getInstance()->videoPlayer($sUrlPoster, $sUrlMP4, $sUrlWebM, false, 'height:200px;');
            echo '<hr class="bx-def-hr" />';
        }
    }


    $a = $oStorageOrig->getFilesAll();
?>
    <h2>Files List</h2>
    <form method="POST">
        <?php foreach ($a as $r): ?>
            <input type="checkbox" name="file_id[]" value="<?=$r['id'] ?>" />
            <?=$r['file_name'] ?>
            <br />
        <?php endforeach; ?>
        <input type="submit" name="delete" value="Delete" class="bx-btn bx-btn-small bx-def-margin-sec-top" style="float:none;" />
    </form>
    <hr class="bx-def-hr" /> 


    <h2>Upload</h2>
    <form enctype="multipart/form-data" method="POST">
        <input type="file" name="file" />
        <br />
        <input type="submit" name="upload" value="Upload" class="bx-btn bx-btn-small bx-def-margin-sec-top" style="float:none;" />
    </form>
<?php

    $s = ob_get_clean();
    return DesignBoxContent("Sample video transcoder", $s, BX_DB_PADDING_DEF);

}

/** @} */
