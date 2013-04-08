<?php
    $sBasePath = BX_DIRECTORY_PATH_ROOT . 'modules/boonex/files/data/files/';
    $a = $this->oDb->getAll("SELECT `ID`, `Ext`, `Date` FROM `bx_files_main`");
    foreach ($a as $r) {
        $sPathOld = $sBasePath . $r['ID'] . '.' . $r['Ext'];
        if (!file_exists($sPathOld))
            continue;
        $sPathNew = $sBasePath . $r['ID'] . '_' . sha1($r['Date']);
        @rename($sPathOld, $sPathNew);
    }
    return true;
?>
