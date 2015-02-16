<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioLauncher extends BxTemplStudioWidgets
{
    protected $aIncludes;

    function __construct()
    {
        parent::__construct(BX_DOL_STUDIO_PAGE_HOME);

        $aClasses = array(
            'BxTemplStudioModules',
            'BxTemplStudioDesigns',
            'BxTemplStudioLanguages'
        );

        //--- Check actions ---//
        if(($sAction = bx_get('action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_err_operation_failed'));
            switch($sAction) {
				case 'launcher-update-cache':
					$aResult = $this->updateCache();
					break;

                case 'launcher-reorder':
                    $sPage = bx_process_input(bx_get('page'));
                    $aItems = bx_process_input(bx_get('items'));

                    BxDolStudioWidgetsQuery::getInstance()->reorder($sPage, $aItems);
                    $aResult = array('code' => 0, 'message' => _t('_adm_scs_operation_done'));
                    break;
            }

            echo json_encode($aResult);
            exit;
        }

        $this->aIncludes = array();
        foreach($aClasses as $sClass)
            $this->aIncludes[] = new $sClass();
    }

    function isEnabled($aWidget)
    {
        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($aWidget['module']);
        if(empty($aModule) || !is_array($aModule))
            return true;

        return (int)$aModule['enabled'] == 1;
    }
}

/** @} */
