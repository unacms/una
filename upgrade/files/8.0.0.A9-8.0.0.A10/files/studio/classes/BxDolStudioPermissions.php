<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioPermissionsQuery');

define('BX_DOL_STUDIO_PRM_TYPE_LEVELS', 'levels');
define('BX_DOL_STUDIO_PRM_TYPE_ACTIONS', 'actions');

define('BX_DOL_STUDIO_PRM_TYPE_DEFAULT', BX_DOL_STUDIO_PRM_TYPE_LEVELS);

class BxDolStudioPermissions extends BxTemplStudioPage
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('builder_permissions');

        $this->oDb = new BxDolStudioPermissionsQuery();

        $this->sPage = BX_DOL_STUDIO_PRM_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

    public function init()
	{
		if(($sAction = bx_get('pgt_action')) === false)
			return;

		$sAction = bx_process_input($sAction);

		$aResult = array('code' => 1, 'message' => _t('_adm_pgt_err_cannot_process_action'));
		switch($sAction) {
			case 'get-page-by-type':
				$sValue = bx_process_input(bx_get('pgt_value'));
				if(empty($sValue))
					break;

				$this->sPage = $sValue;
				$aResult = array('code' => 0, 'content' => $this->getPageCode());
				break;
		}

		echo json_encode($aResult);
		exit;
	}
}

/** @} */
