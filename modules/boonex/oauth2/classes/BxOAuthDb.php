<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
 *
 * @{
 */

class BxOAuthDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    function getClientTitle($sClientId)
    {
        $sQuery = $this->prepare("SELECT `title` FROM `bx_oauth_clients` WHERE `client_id` = ?", $sClientId);
        return $this->getOne($sQuery);
    }

    function deleteClients($aClients)
    {        
        foreach ($aClients as $sClientId) {
            $sQuery = $this->prepare("DELETE FROM `bx_oauth_clients` WHERE `client_id` = ?", $sClientId);
            $this->query($sQuery);
        }
    }
}

/** @} */
