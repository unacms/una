<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Menu to set acl level for profile
 */
class BxBaseMenuSetAclLevel extends BxTemplMenu 
{
    public function __construct ($aObject, $oTemplate) 
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode () 
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && ($iProfileId = bx_get('profile_id', 'post')) && ($iAclLevelId = bx_get('acl_level_id', 'post'))) {

            $sMsg = '';
            bx_import('BxDolAcl');
            
            $aCheck = checkActionModule($iProfileId, 'set acl level', 'system', false);
            if (isAdmin() || $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED) {

                $oAcl = BxDolAcl::getInstance();
                if ($oAcl->setMembership($iProfileId, $iAclLevelId, 0, true))
                    checkActionModule($iProfileId, 'delete account', 'system', true); // perform action
                else
                    $sMsg = _t('_error occured');
                
            } else {
                $sMsg = $aCheck[CHECK_ACTION_MESSAGE];
            }

            header('Content-type: text/html; charset=utf-8');
            echo $sMsg;
            exit;
        }

        return parent::getCode ();
    }

    public function getMenuItems () 
    {
		$this->loadData();
    	return parent::getMenuItems();
    }

	protected function loadData() 
    {
        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        bx_import('BxDolAcl');
        $oAcl = BxDolAcl::getInstance();
        if (!$oAcl)
            return;

        $aProfileAclLevel = $oAcl->getMemberMembershipInfo($iProfileId);
        $aAclLevels = $oAcl->getMemberships(false, true);


        $this->setSelected('', $aProfileAclLevel['id']);

        $aItems = array();
        foreach ($aAclLevels as $iId => $sTitle) {        	
        	$aItems[] = array(
        		'id' => $iId, 
        		'name' => $iId,
        		'class' => '', 
        		'title' => $sTitle, 
        		'icon' => '',
        		'link' => 'javascript:void(0);',
        		'onclick' => "bx_set_acl_level({$iProfileId}, {$iId}, '#bx-popup-ajax-wrapper-sys_set_acl_level');"
        	);
        }

        $this->_aObject['menu_items'] = $aItems;
    }
}

/** @} */
