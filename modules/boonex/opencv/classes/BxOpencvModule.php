<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OpenCV OpenCV integration
 * @ingroup     UnaModules
 *
 * @{
 */

use CV\Scalar;
use function CV\{imread, imwrite, rectangle, medianBlur, addWeighted};

class BxOpencvModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetStorages()
    {
        $aStorageObjects = BxDolStorageQuery::getStorageObjects();
        $a = [];
        foreach ($aStorageObjects as $r)
            $a[$r['object']] = $r['object'];
        return $a;
    }

    public function serviceProcessImage($iProfileId, $sFilePath, $sExt = 'jpg')
    {
        $src = imread($sFilePath);
        $blr = null;

        $netDet = CV\FaceDetectorYN::create(BX_DIRECTORY_PATH_MODULES . 'boonex/opencv/models/opencv_zoo/face_detection_yunet_2022mar.onnx', '', $src->size());

        if ($r = $netDet->detect($src)) {
            $dst = $src->clone();
            medianBlur($src, $dst, 0 == (int)($src->cols / 70)%2 ? 1 + (int)($src->cols / 70) : (int)($src->cols / 70));
        }

        $fConfidence = (float)getParam('bx_opencv_option_confidence');

        $faces = [];
        $bDetection = false;
        for ($i = 0; $i < $r->rows; $i++) {
            $confidence = $r->atIdx([$i,14]);

            if ($confidence > $fConfidence) {
                $startX = $r->atIdx([$i,0]);
                $startY = $r->atIdx([$i,1]);
                $w = $r->atIdx([$i,2]);
                $h = $r->atIdx([$i,3]);

                $this->_blur($src, $dst, (int)$startX, (int)$startY, (int)$w, (int)$h);

                $bDetection = true;
            }
        }

        if ($bDetection) {
            $sExtReal = pathinfo($sFilePath, PATHINFO_EXTENSION);
            if ($sExtReal) {
                imwrite($sFilePath, $src);
            }
            else {
                imwrite($sFilePath . '.' . $sExt, $src);
                if (file_exists($sFilePath . '.' . $sExt) && unlink($sFilePath))
                    rename($sFilePath . '.' . $sExt, $sFilePath);
            }
        }
    }

    private function _blur(&$src, $dst, $x, $y, $w, $h)
    {
        $iChannels = $src->channels();
        for  ($j = 1; $j < $src->rows - 1; ++$j) {
            for  ($i = 1; $i < $src->cols - 1; ++$i) {
                for  ($ch = 0; $ch < $iChannels; $ch++) {
                    if ($i >= $x && $i <= $x + $w && $j >= $y && $j <= $y + $h) {
                        $val = $dst->at($j, $i, $ch);
                        $src->at($j, $i, $ch, $val);
                    }
                }
            }
        }
    }
}

/** @} */
