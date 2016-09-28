<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioDesignerQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    public function updateInjection($sName, $sValue)
    {
		return (int)$this->query("UPDATE `sys_injections` SET `data`=:data WHERE `name`=:name", array(
			'name' => $sName,
			'data' => $sValue
		)) > 0;
    }
    
}

/** @} */
