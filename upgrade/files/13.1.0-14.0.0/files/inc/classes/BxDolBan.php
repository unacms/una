<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolBan extends BxDolConnection
{
    protected function __construct($aObject)
    {
        parent::__construct($aObject);
    }  

    /**
     * Add new connection.
     * @param $iInitiator initiator of the connection, in most cases some profile id
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return true - if connection was added, false - if connection already exists or error occured
     */
    public function addConnection($iInitiator, $iContent, $aParams = [])
    {
        $bResult = parent::addConnection($iInitiator, $iContent, $aParams);
        if($bResult)
            $this->_oQuery->updateConnection($iInitiator, $iContent, [
                'module' => !empty($aParams['module']) ? $aParams['module'] : BxDolProfile::getInstance($iInitiator)->getModule(),
            ]);

        return $bResult;
    }
}