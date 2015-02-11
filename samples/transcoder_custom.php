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
 * @section transcoder_images_custom Custom Images Transcoder
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Custom images transcoder");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $sTranscoderObject = 'sys_custom_images';
    $sStorageObjectOrig = 'sys_images_custom';
    $iProfileId = bx_get_logged_profile_id();

    if (!isAdmin()) {
        echo "You aren't operator.";
        exit;
    }

    $iPrunedFiles = BxDolTranscoder::pruning();
    if ($iPrunedFiles) {
        echo "iPrunedFiles: $iPrunedFiles";
        exit;
    }
    $oTranscoderObject = BxDolTranscoderImage::getObjectInstance($sTranscoderObject);
    if (!$oTranscoderObject) {
        echo "Transcoder object is not available: " . $sTranscoderObject;
        exit;
    }
    //echo "registerHandlers: [" . $oTranscoderObject->registerHandlers() . "] <br />\n";

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
            $sUrlImage = $oTranscoderObject->getFileUrl($r['id']);
?>
            <div class="bx-def-font-h2 bx-def-margin-sec-topbottom"><?=$r['file_name']; ?></div>
            Size: <input id="size-<?=$r['id']; ?>" type="text" class="bx-def-font-inputs bx-form-input-text bx-def-margin-sec-bottom bx-transcoder-custom-images-size" placeholder="320x240" />
            Copy&amp;paste URL: <input id="input-<?=$r['id']; ?>" type="text" value="<?=$oTranscoderObject->getFileUrlNotReady($r['id']); ?>" class="bx-def-font-inputs bx-form-input-text bx-def-margin-sec-bottom" /> <br />
            <img src="<?=$sUrlImage; ?>" style="height:200px; width:auto;" />
            <hr class="bx-def-hr" />
<?php
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

    <script>
        $(document).ready(function () {
            $('.bx-transcoder-custom-images-size').on('change input', function () {
                var sId = $(this).attr('id').replace('size-', '');
                var a = $('#input-' + sId).val().split('&');
                var aSize = this.value.split('x');
                var s = '';
                for (var i in a) {
                    if (-1 == a[i].indexOf('t=') && -1 == a[i].indexOf('x=') && -1 == a[i].indexOf('y='))
                        s += a[i] + '&';
                }

                s = s.replace(/&$/, '');
                
                if (parseInt(aSize[0]) > 0)
                    s = bx_append_url_params(s, 'x=' + aSize[0]);
                if (parseInt(aSize[1]) > 0)
                    s = bx_append_url_params(s, 'y=' + aSize[1]);

                $('#input-' + sId).val(s);
            });
        });
    </script>
<?php

    $s = ob_get_clean();
    return DesignBoxContent("Custom images transcoder", $s, BX_DB_PADDING_DEF);

}

/** @} */
