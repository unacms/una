<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for BxDolQueueEmail object.
 * @see BxDolQueueEmail
 */
class BxDolQueueEmailQuery extends BxDolQueueQuery
{
    public function __construct()
    {
        parent::__construct();

        $this->_sTable = 'sys_queue_email';
    }
}

/** @} */
