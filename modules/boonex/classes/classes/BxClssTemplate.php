<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxClssTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_classes';

        parent::__construct($oConfig, $oDb);
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);

        return array($sPhotoGallery, $sPhotoGallery);
    }

    public function getTitle($aData, $mixedProcessOutput = BX_DATA_TEXT)
    {
        $sTitle = parent::getTitle($aData, $mixedProcessOutput);

        if ($this->_oModule->serviceIsClassCompleted($aData['id'])) {
            $sStatusClass = 'bx-classes-class-status-completed';
        }
        else {
            $sStatusClass = 'bx-classes-class-status-avail';
        }

        return $this->parseHtmlByName('classes_class_title.html', array(
            'status' => $sStatusClass,
            'bx_if:completed' => array(
                'condition' => 'bx-classes-class-status-completed' == $sStatusClass,
                'content' => array('title' => $sTitle),
            ),
            'bx_if:avail' => array(
                'condition' => 'bx-classes-class-status-avail' == $sStatusClass,
                'content' => array('title' => $sTitle),
            ),
        ));
    }
}

/** @} */
