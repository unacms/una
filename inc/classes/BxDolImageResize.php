<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

// Function error codes
define( 'IMAGE_ERROR_SUCCESS',               0 );
define( 'IMAGE_ERROR_SOURCE_NOT_EXISTS',     1 );
define( 'IMAGE_ERROR_WRONG_TYPE',            2 );
define( 'IMAGE_ERROR_FILE_OPEN_FAILED',      3 );
define( 'IMAGE_ERROR_IMAGEMAGICK_ERROR',     4 );
define( 'IMAGE_ERROR_GD_NOT_INSTALLED',      5 );
define( 'IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED', 6 );
define( 'IMAGE_ERROR_GD_OPEN_FAILED',        7 );
define( 'IMAGE_ERROR_GD_RESIZE_ERROR',       8 );
define( 'IMAGE_ERROR_GD_MERGE_ERROR',        9 );
define( 'IMAGE_ERROR_GD_WRITE_FAILED',       10 );
define( 'IMAGE_ERROR_GD_TTF_NOT_SUPPORTED',  11 );
define( 'IMAGE_ERROR_GD_FILTER_ERROR',       12 );

// Image types for GD
// NOTE: actually these constants exist in PHP >= 4.3.0, but they are included for
//       back compatibility
define( 'IMAGE_TYPE_GIF',         1 );
define( 'IMAGE_TYPE_JPG',         2 );
define( 'IMAGE_TYPE_PNG',         3 );

class BxDolImageResize extends BxDol implements iBxDolSingleton
{
    protected $w = 64, $h = 64; ///< size of destination image
    protected $_isCrop = false;
    protected $_isAutoCrop = false;
    protected $_cropX, $_cropY, $_cropW, $_cropH;
    protected $_iForceOutputType = false; ///< force IMAGE_TYPE_PNG, IMAGE_TYPE_PNG, IMAGE_TYPE_GIF or false to keep the original format // TODO: check if this setting works when ImageMagick is used
    protected $_iJpegQuality = 90; ///< jpeg quality
    protected $_isSquareResize = false; ///< use smart resize, destination image will be exact Width x Height size
    protected $_isUseGD; ///< use GD library or command line ImagMagic utilites

    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_isUseGD = getParam('enable_gd') == 'on' && extension_loaded('gd') ? true : false;
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolImageResize();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function isAllowedImage ($sPath)
    {
        if ($this->_isUseGD) {
            $aImage = getimagesize($sPath);
            if (!empty($aImage) && in_array($aImage[2], array(1, 2, 3)))
                return true;
        } else {
            // TODO: image checking using image magick
        }
        return false;
    }

    function resize ($mixedImage, $sDstImage = '')
    {
        if (is_array($mixedImage)) {
            $aRet = array();
            foreach ($mixedImage as $s) {
                $aRet[] = $this->_resize ($s, $s);
            }
            return $aRet;
        } else {
            return $this->_resize ($mixedImage, $sDstImage);
        }
    }

    function applyWatermark ($mixedImage, $sDstImage, $wtrFilename, $wtrTransparency)
    {
        if (is_array($mixedImage)) {
            $aRet = array();
            foreach ($mixedImage as $s) {
                $aRet[] = $this->_applyWatermark ($s, $s, $wtrFilename, $wtrTransparency);
            }
            return $aRet;
        } else {
            return $this->_applyWatermark ($mixedImage, $sDstImage, $wtrFilename, $wtrTransparency);
        }
    }

    function grayscale ($mixedImage, $sDstImage = '')
    {
        if (is_array($mixedImage)) {
            $aRet = array();
            foreach ($mixedImage as $s) {
                $aRet[] = $this->_grayscale ($s, $s);
            }
            return $aRet;
        } else {
            return $this->_grayscale ($mixedImage, $sDstImage);
        }
    }

    function setSize ($w, $h)
    {
        $this->w = $w;
        $this->h = $h;
    }

    function removeCropOptions ()
    {
        $this->_isCrop = false;
        $this->_isAutoCrop = false;
    }

    function setCropOptions ($x, $y, $w, $h)
    {
        $this->_isCrop = true;
        $this->_cropX = $x;
        $this->_cropY = $y;
        $this->_cropW = $w;
        $this->_cropH = $h;
    }

    /**
     * Crop image to destination size with filling whole area of destination size
     */
    function setAutoCrop ($b)
    {
        $this->_isAutoCrop = $b;
    }

    function setJpegOutput ($b)
    {
        $this->setOutputType($b ? IMAGE_TYPE_JPG : false);
    }

    function setOutputType ($iType)
    {
        $this->_iForceOutputType = $iType;;
    }

    function setJpegQuality ($i)
    {
        $this->_iJpegQuality = $i;
    }

    function setSquareResize ($b)
    {
        $this->_isSquareResize = ($b ? true : false);
    }

    function isUsedGD ()
    {
        return $this->_isUseGD;
    }

    function getImageSize($sPath)
    {
        $aSize = getimagesize($sPath);
        return array ('w' => $aSize[0], 'h' => $aSize[1]);
    }

    function getAverageColor($sPath)
    {
    	return $this->isUsedGD() ? $this->_getAverageColorGD($sPath) : $this->_getAverageColorImageMagic($sPath);
    }

    // private functions are below -------------------------------

    function _grayscale ($sSrcImage, $sDstImage = '')
    {
        if (!file_exists($sSrcImage))
            return IMAGE_ERROR_SOURCE_NOT_EXISTS;

        if (!$sDstImage)
            $sDstImage = $sSrcImage;

        if ($sDstImage == $sSrcImage) {
            chmod($sDstImage, 0666);
        }

        return $this->_isUseGD ? $this->_grayscaleGD ($sSrcImage, $sDstImage) : $this->_grayscaleImageMagick ($sSrcImage, $sDstImage);
    }

    function _grayscaleGD ($sSrcImage, $sDstImage)
    {
        $iErr = 0;
        $src_im =& $this->_createGDImage($sSrcImage, $size, $iErr);

        if ($iErr)
            return $iErr;
        if (!$src_im)
            return IMAGE_ERROR_GD_OPEN_FAILED;

        if (!imagefilter($src_im, IMG_FILTER_GRAYSCALE))
            return IMAGE_ERROR_GD_FILTER_ERROR;

        $writeResult = $this->_writeImageGD ($src_im, $sDstImage, $size[2]);

        // free memory
        imagedestroy( $src_im );

        if ( $writeResult && file_exists($sDstImage) )
            return IMAGE_ERROR_SUCCESS;

        return IMAGE_ERROR_GD_WRITE_FAILED;
    }

    function _grayscaleImageMagick ($sSrcImage, $sDstImage)
    {
        $cmd = "{$GLOBALS['CONVERT']} \"$sSrcImage\" -type Grayscale -quantize Gray \"$sDstImage\"";
        @exec( $cmd );

        if ( file_exists($sDstImage) )
            return IMAGE_ERROR_SUCCESS;

        return IMAGE_ERROR_IMAGEMAGICK_ERROR;
    }

    function _resize ($sSrcImage, $sDstImage = '')
    {
        if (!file_exists($sSrcImage))
            return IMAGE_ERROR_SOURCE_NOT_EXISTS;

        if (!$sDstImage)
            $sDstImage = $sSrcImage;

        if ($sDstImage == $sSrcImage) {
            chmod($sDstImage, 0666);
        }

        return $this->_isUseGD ? $this->_resizeGD ($sSrcImage, $sDstImage) : $this->_resizeImageMagick ($sSrcImage, $sDstImage);
    }

    function _resizeGD ($sSrcImage, $sDstImage)
    {
        $iErr = 0;
        $src_im =& $this->_createGDImage($sSrcImage, $size, $iErr);
        $sizeOrig = $size;

        if ($iErr)
            return $iErr;
        if (!$src_im)
            return IMAGE_ERROR_GD_OPEN_FAILED;

        $xd = $yd = 0;
        $xs = $ys = 0;

        if ($this->_isAutoCrop) {
            $sourceRatio = (float) ($size[0] / $size[1]);
            $destRatio = (float) ($this->w / $this->h);
            if ( $sourceRatio > $destRatio )
                $resizeRatio = (float) ($this->h / $size[1]);
            else
                $resizeRatio = (float) ($this->w / $size[0]);
            $destW = (int) ($resizeRatio * $size[0]);
            $destH = (int) ($resizeRatio * $size[1]);

            if ($destW > $this->w)
                $this->setCropOptions (floor(($size[0] - $this->w/$resizeRatio)/2.0), 0, floor($this->w/$resizeRatio), $size[1]);
            elseif ($destH > $this->h)
                $this->setCropOptions (0, floor(($size[1] - $this->h/$resizeRatio)/2.0), $size[0], floor($this->h/$resizeRatio));
        }

        if ($this->_isCrop) {
            $size[0] = $this->_cropW;
            $size[1] = $this->_cropH;

            $xs = $this->_cropX;
            $ys = $this->_cropY;

            $destW = $this->w;
            $destH = $this->h;

        } elseif ($this->_isSquareResize) {

            $destW = $this->w;
            $destH = $this->h;

            if ($size[0] < $size[1]) {

                $d = ($size[1] - $size[0])/2;
                $size[1] = $size[0];

                $xs = 0;
                $ys = (int)$d;

            } else {

                $d = ($size[0] - $size[1])/2;
                $size[0] = $size[1];

                $xs = (int)$d;
                $ys = 0;

            }

        } else {

            // determ destination size
            $sourceRatio = (float) ($size[0] / $size[1]);
            $destRatio = (float) ($this->w / $this->h);
            if ( $sourceRatio > $destRatio )
                $resizeRatio = (float) ($this->w / $size[0]);
            else
                $resizeRatio = (float) ($this->h / $size[1]);
            $destW = (int) ($resizeRatio * $size[0]);
            $destH = (int) ($resizeRatio * $size[1]);

        }

        // this is more qualitative function, but it doesn't exist in old GD and doesn't support GIF format
        if ( function_exists( 'imagecreatetruecolor' ) && $size[2] != IMAGE_TYPE_GIF ) {
            // resize only if size is larger than needed
            if ( $this->_isCrop || $sizeOrig[0] > $this->w || $sizeOrig[1] > $this->h ) {
                $dst_im = imagecreatetruecolor( $destW, $destH );
                imagecolortransparent($dst_im, imagecolorallocate($dst_im, 0, 0, 0));
                imagealphablending($dst_im, false);
                imagesavealpha($dst_im, true);
                $convertResult = imagecopyresampled( $dst_im, $src_im, $xd, $yd, $xs, $ys,
                    $destW, $destH, $size[0], $size[1] );
            } else {
                $dst_im = $src_im;
                $convertResult = true;
            }
        } else { // this is for old GD versions and for GIF images
            // resize only if size is larger than needed
            if ( $size[0] > $this->w || $size[1] > $this->h ) {
                $dst_im = imagecreate( $destW, $destH );
                if ($size[2] == IMAGE_TYPE_GIF) {
                    ImageColorTransparent( $dst_im, imagecolorallocate($dst_im, 0, 0, 0) );
                    imagealphablending( $dst_im, false );
                }
                $convertResult = imagecopyresized( $dst_im, $src_im, $xd, $yd, $xs, $ys,
                    $destW, $destH, $size[0], $size[1] );
            } else {
                $dst_im = $src_im;
                $convertResult = true;
            }
        }

        if ( !$convertResult )
            return IMAGE_ERROR_GD_RESIZE_ERROR;

        $writeResult = $this->_writeImageGD ($dst_im, $sDstImage, $size[2]);

        // free memory
        if ( $dst_im != $src_im ) {
            imagedestroy( $src_im );
            imagedestroy( $dst_im );
        } else {
            imagedestroy( $src_im );
        }

        if ( $writeResult && file_exists($sDstImage) )
            return IMAGE_ERROR_SUCCESS;

        return IMAGE_ERROR_GD_WRITE_FAILED;
    }

    function _resizeImageMagick ($sSrcImage, $sDstImage)
    {
        // TODO: $this->_isCrop and $this->_isSquareResize
        if ( $sSrcImage == $sDstImage ) {
            $cmd = "{$GLOBALS['MOGRIFY']} -geometry {$this->w}\">\"x{$this->h}\">\" \"$sSrcImage\"";
            @exec( $cmd );
            $nameWithoutExt = substr( $sSrcImage, 0, strrpos($sSrcImage, '.') );
            if ( file_exists( "{$nameWithoutExt}.mgk" ) )
                rename( "{$nameWithoutExt}.mgk", $sSrcImage );
        } else {
            $cmd = "{$GLOBALS['CONVERT']} \"$sSrcImage\" -geometry {$this->w}\">\"x{$this->h}\">\" \"$sDstImage\"";
            @exec( $cmd );
        }

        if ( file_exists($sDstImage) )
            return IMAGE_ERROR_SUCCESS;

        return IMAGE_ERROR_IMAGEMAGICK_ERROR;
    }

    function _applyWatermark( $srcFilename, $dstFilename, $wtrFilename, $wtrTransparency )
    {
        // TODO: watermard are not applied correctly, becuase images of different suzes are used, FIX IT

        // input validation
        $wtrTransparency = (int) $wtrTransparency;
        if ( $wtrTransparency > 100 )
            $wtrTransparency = 100;
        if ( !file_exists( $srcFilename ) )
            return IMAGE_ERROR_SOURCE_NOT_EXISTS;
        if ( !file_exists( $wtrFilename ) )
            return IMAGE_ERROR_SOURCE_NOT_EXISTS;

        // if destination and source filenames are equivalent then change mode for destination
        if ( $srcFilename == $dstFilename )
            chmod( $dstFilename, 0666 );

        if ( $this->_isUseGD ) {
            return $this->_applyWatermarkGD($srcFilename, $dstFilename, $wtrFilename, $wtrTransparency);
        } else {
            return $this->_applyWatermarkImageMagick($srcFilename, $dstFilename, $wtrFilename, $wtrTransparency);
        }
    }

    function _applyWatermarkGD($srcFilename, $dstFilename, $wtrFilename, $wtrTransparency)
    {
        $iErr = 0;

        $src_im =& $this->_createGDImage($srcFilename, $size, $iErr);
        if ($iErr)
            return $iErr;
        if (!$src_im)
            return IMAGE_ERROR_GD_OPEN_FAILED;

        $wtr_im =& $this->_createGDImage($wtrFilename, $wtrSize, $iErr);
        if ($iErr)
            return $iErr;

        if (!$wtr_im )
            return IMAGE_ERROR_GD_OPEN_FAILED;

        if (function_exists('imagecreatetruecolor'))
            $dst_im = imagecreatetruecolor( $size[0], $size[1] );
        else
            $dst_im = imagecreate( $size[0], $size[1] );

        $watermarkX = ($size[0] - $wtrSize[0]) / 2;
        $watermarkY = $size[1] - $wtrSize[1];

        $copyResult = imagecopy( $dst_im, $src_im, 0, 0, 0, 0, $size[0], $size[1]);
        if (!$copyResult)
            return IMAGE_ERROR_GD_MERGE_ERROR;

        imagealphablending($wtr_im, false);
        imagesavealpha($wtr_im, true);

        if (IMAGE_TYPE_PNG == $wtrSize[2] && imageistruecolor($wtr_im))
            $mergeResult = imagecopy  ( $dst_im, $wtr_im, $watermarkX, $watermarkY, 0, 0, $wtrSize[0], $wtrSize[1]);
        else
            $mergeResult = imagecopymerge  ( $dst_im, $wtr_im, $watermarkX, $watermarkY, 0, 0, $wtrSize[0], $wtrSize[1], $wtrTransparency );

        if ( !$mergeResult )
            return IMAGE_ERROR_GD_MERGE_ERROR;

        switch ( $size[2] ) {
            case IMAGE_TYPE_GIF:
                $writeResult = imagegif( $dst_im, $dstFilename );
                break;
            case IMAGE_TYPE_JPG:
                $writeResult = imagejpeg( $dst_im, $dstFilename, $this->_iJpegQuality);
                break;
            case IMAGE_TYPE_PNG:
                $writeResult = imagepng( $dst_im, $dstFilename );
                break;
        }

        // free memory
        imagedestroy( $dst_im );
        imagedestroy( $src_im );
        imagedestroy( $wtr_im );

        if ( $writeResult )
            return IMAGE_ERROR_SUCCESS;
        else
            return IMAGE_ERROR_GD_WRITE_FAILED;
    }

    function _applyWatermarkImageMagick($srcFilename, $dstFilename, $wtrFilename, $wtrTransparency)
    {
        $imgTransparency = 100 - $wtrTransparency;
        $cmd = "{$GLOBALS['COMPOSITE']} -gravity \"South\" -dissolve \"$imgTransparency\" \"$wtrFilename\" -dissolve $wtrTransparency \"$srcFilename\" \"$dstFilename\"";
        @exec( $cmd );

        if ( file_exists($dstFilename) )
            return IMAGE_ERROR_SUCCESS;

        return IMAGE_ERROR_IMAGEMAGICK_ERROR;
    }

    function & _createGDImage($s, &$aSize, &$iErr) {

        if (!isset($this->gdInfoArray))
            $this->gdInfoArray = gd_info();

        $aSize = getimagesize($s);

        // only GIF, JPG and PNG allowed
        switch ( $aSize[2] ) {
            case IMAGE_TYPE_GIF:
                if (!isset($this->gdInfoArray['GIF Read Support']) || !$this->gdInfoArray['GIF Read Support'] || !isset($this->gdInfoArray['GIF Create Support']) || !$this->gdInfoArray['GIF Create Support'])
                    return ($iErr = IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED);
                $r = imagecreatefromgif( $s );
                return $r;
            case IMAGE_TYPE_JPG:
                if ((!isset($this->gdInfoArray['JPG Support']) || !$this->gdInfoArray['JPG Support']) && (!isset($this->gdInfoArray['JPEG Support']) || !$this->gdInfoArray['JPEG Support']))
                    return ($iErr = IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED);
                $r = imagecreatefromjpeg( $s );
                return $r;
            case IMAGE_TYPE_PNG:
                if (!isset($this->gdInfoArray['PNG Support']) || !$this->gdInfoArray['PNG Support'])
                    return ($iErr = IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED);
                $r = imagecreatefrompng( $s );
                imagesavealpha($r, true);
                return $r;
            default:
                return ($iErr = IMAGE_ERROR_WRONG_TYPE);
        }
    }

    function _writeImageGD ($src_im, $sDstImage, $iSrcImageType)
    {
        $writeResult = false;

        switch ($this->_iForceOutputType){

            case IMAGE_TYPE_JPG:
                $writeResult = imagejpeg( $src_im, $sDstImage, $this->_iJpegQuality);
                break;

            case IMAGE_TYPE_PNG:
                $writeResult = imagepng( $src_im, $sDstImage);
                break;

            case IMAGE_TYPE_GIF:
                $writeResult = imagegif( $src_im, $sDstImage );
                break;

            default:
                switch ($iSrcImageType) {
                    case IMAGE_TYPE_GIF:
                        $writeResult = imagegif( $src_im, $sDstImage );
                        break;
                    case IMAGE_TYPE_PNG:
                        $writeResult = imagepng( $src_im, $sDstImage );
                        break;
                    case IMAGE_TYPE_JPG:
                    default:
                        $writeResult = imagejpeg( $src_im, $sDstImage, $this->_iJpegQuality);
                        break;
                }
        }

        return $writeResult;
    }

	function _getAverageColorGD($sPath)
    {
    	$iError = 0;
		$aSize = array();
		$oImgOrig = $this->_createGDImage($sPath, $aSize, $iError);
		if($iError != IMAGE_ERROR_SUCCESS)
			return false;

		$oImgTmp = ImageCreateTrueColor(1,1);
		ImageCopyResampled($oImgTmp, $oImgOrig, 0, 0, 0, 0, 1, 1, $aSize[0],$aSize[1]);
		$iRgb = ImageColorAt($oImgTmp, 0, 0);

		$iRed = ($iRgb >> 16) & 0xFF;
		$iGreen = ($iRgb >> 8) & 0xFF;
		$iBlue = $iRgb & 0xFF;

		return array('r' => $iRed, 'g' => $iGreen, 'b' => $iBlue);
    }

    function _getAverageColorImageMagic($sPath)
    {
    	$sCmd = "{$GLOBALS['CONVERT']} $sPath -filter box -resize 1x1! -format \"%[fx:round(255*u.r)],%[fx:round(255*u.g)],%[fx:round(255*u.b)]\" info:";
        $sResult = @exec($sCmd);
        if(empty($sResult))
        	return false;

        $aResult = explode(',', $sResult);
		if(count($aResult) != 3)
			return false;

		return array('r' => $aResult[0], 'g' => $aResult[1], 'b' => $aResult[2]);
    }
}

/** @} */
