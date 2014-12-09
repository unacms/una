<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioNavigationQuery');

define('BX_DOL_STUDIO_NAV_TYPE_MENUS', 'menus');
define('BX_DOL_STUDIO_NAV_TYPE_SETS', 'sets');
define('BX_DOL_STUDIO_NAV_TYPE_ITEMS', 'items');

define('BX_DOL_STUDIO_NAV_TYPE_DEFAULT', BX_DOL_STUDIO_NAV_TYPE_MENUS);

class BxDolStudioNavigation extends BxTemplStudioPage
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('builder_menus');

        $this->oDb = new BxDolStudioNavigationQuery();

        $this->sPage = BX_DOL_STUDIO_NAV_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

    public function init()
	{
        if(($sAction = bx_get('nav_action')) === false) 
        	return;

		$sAction = bx_process_input($sAction);

		$aResult = array('code' => 1, 'message' => _t('_adm_nav_err_cannot_process_action'));
		switch($sAction) {
			case 'get-page-by-type':
				$sValue = bx_process_input(bx_get('nav_value'));
				if(empty($sValue))
					break;

				$this->sPage = $sValue;
				$aResult = array('code' => 0, 'content' => $this->getPageCode());
				break;

			default:
				$sMethod = 'action' . $this->getClassName($sAction);
				if(method_exists($this, $sMethod))
					$aResult = $this->$sMethod();
		}

		echo json_encode($aResult);
		exit;
	}
}

/** @} */
