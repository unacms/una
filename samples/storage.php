<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample storage");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    ?>
<form enctype="multipart/form-data" method="POST">
    Choose a file to upload:
    <input name="file" type="file" />
    <br />
    <input type="submit" name="add" value="Upload File" />
</form>
    <?php

    $iProfileId = 123;
    BxDolStorage::pruning(); // pruning is needed to clear expired security tokens, you can call it on cron when your server is not busy
    $oStorage = BxDolStorage::getObjectInstance('sample');

    //echo '<pre>reloadMimeTypesFromFile: [' . $oStorage->reloadMimeTypesFromFile(/*'/Users/alex/mime.types'*/'/etc/apache2/mime.types') . ']</pre>';

    if (isset($_POST['add'])) {
        $iId = $oStorage->storeFileFromForm($_FILES['file'], true, $iProfileId);
        if ($iId) {
            $iCount = $oStorage->afterUploadCleanup($iId, $iProfileId);
            echo "<hr />uploaded file id: " . $iId . "(deleted ghosts:" . $iCount . ")<hr />";
        } else {
            echo "<hr />error uploading file: " . $oStorage->getErrorString() . "<hr />";
        }
    }

    if (isset($_POST['delete'])) {
        foreach ($_POST['file_id'] as $iFileId) {
            $bRet = $oStorage->deleteFile($iFileId, $iProfileId);
            if ($bRet)
                echo "<hr />deleted file id: " . $iFileId . "<hr />";
            else
                echo "<hr />file deleting error: " . $oStorage->getErrorString() . "<hr />";
        }
    } elseif (isset($_POST['public']) || isset($_POST['private'])) {
        $isPrivate = isset($_POST['private']) && $_POST['private'] ? true : false;
        $sAction = $isPrivate ? 'private' : 'public';
        foreach ($_POST['file_id'] as $iFileId) {
            $bRet = $oStorage->setFilePrivate($iFileId, $isPrivate);
            if ($bRet)
                echo "<hr /> making <b>$sAction</b> file id: " . $iFileId . "<hr />";
            else
                echo "<hr />file making <b>$sAction</b> error: " . $oStorage->getErrorString() . "<hr />";
        }
    }

    $a = $oStorage->getFilesAll();
    echo "<h2>Files List:</h2> <hr />";
    foreach ($a as $r)
        echo $r['file_name'] . '(private:[' . $oStorage->isFilePrivate($r['id']) . ']) : <img src="' . $oStorage->getFileUrlById($r['id']) . '" /> <hr />';

    echo '<form method="POST">';
    foreach ($a as $r)
        echo '<input type="checkbox" name="file_id[]" value="' . $r['id'] . '" /> ' . $r['file_name'] . '<br />';
    echo '<input type="submit" name="delete" value="Delete" /> <input type="submit" name="public" value="Set Public" /> <input type="submit" name="private" value="Set Private" />';
    echo '</form>';

    $s = ob_get_clean();
    return DesignBoxContent("Sample storage", $s, BX_DB_PADDING_DEF);

}

/** @} */
