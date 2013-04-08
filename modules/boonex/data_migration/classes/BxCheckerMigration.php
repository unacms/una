<?php

    class BxCheckerMigration extends BxDolFormCheckerHelper
    {
        function checkDolpinDirectory($sPath)
        {
            if ( substr( $sPath, strlen($sPath) - 1 ) != DIRECTORY_SEPARATOR ) {
                $sPath .= DIRECTORY_SEPARATOR;
            }

            return is_dir($sPath) && is_file($sPath . 'inc/header.inc.php') ? true : false;
        }
    }