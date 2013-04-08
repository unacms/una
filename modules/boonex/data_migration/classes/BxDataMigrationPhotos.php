<?php

require_once('BxDataMigrationData.php');

class BxDataMigrationPhotos extends BxDataMigrationData {
    var $aPhotoObjects;
    function BxDataMigrationPhotos (&$oMigrationModule, &$rOldDb, $oDolModule = '') {
        parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        $this->aPhotoObjects = array(
            'profile_photos' => array(
                'class' => 'BxDataMigrationProfilePhotos',
                'file'  => 'BxDataMigrationProfilePhotos.php'
            ),
            'shared_photos' => array(
                'class' => 'BxDataMigrationSharedPhotos',
                'file'  => 'BxDataMigrationSharedPhotos.php'
            )
        );
    }

    function getMigration () {
        $sResult = '';
        foreach ($this->aPhotoObjects as $aValue) {
            require_once($aValue['file']);
            $oClass = new $aValue['class']($this->oMigrationModule, $this->rOldDb, $this->oDolModule);
            $sResult =  $oClass->getMigration();
            if($sResult != MIGRATION_SUCCESSFUL) {
                return $sResult;
            }
        }

        return $sResult;
    }
}

?>