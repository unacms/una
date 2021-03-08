<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * File storage in Amazon S3 with signature v4 authorisation.
 * @see BxDolStorage
 */
class BxDolStorageS3v4 extends BxDolStorageS3
{
    protected function init ($aObject)
    {
        require_once(BX_DIRECTORY_PATH_PLUGINS . 'amazon-s3/S3.php');
        require_once(BX_DIRECTORY_PATH_PLUGINS . 'amazon-s3/S3v4.php');
        $this->_s3 = new S3v4\S3(
            getParam('sys_storage_s3_access_key'), 
            getParam('sys_storage_s3_secret_key'), 
            $this->_bSSL, 
            $this->_sEndpoint ? $this->_sEndpoint : 's3.amazonaws.com',
            getParam('sys_storage_s3_region')
        );
    }
}

/** @} */
