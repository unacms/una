<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for BxDolQueuePush object.
 * @see BxDolQueuePush
 */
class BxDolQueuePushQuery extends BxDolQueueQuery
{
    public function __construct()
    {
        parent::__construct();

        $this->_sTable = 'sys_queue_push';
    }
}

/** @} */
