<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu to set acl level for profile
 */
class BxBaseMenuSetAclLevel extends BxTemplMenu
{
	protected $mixedProfileId;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ($mixedProfileId = 0)
    {
    	$this->mixedProfileId = $mixedProfileId;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && ($mixedProfileId = bx_get('profile_id', 'post')) && ($iAclLevelId = bx_get('acl_level_id', 'post'))) {
            $mixedProfileId = urldecode($mixedProfileId);
            if(!is_numeric($mixedProfileId))
                $mixedProfileId = unserialize($mixedProfileId);

            echoJson($this->setMembership($mixedProfileId, $iAclLevelId, (int)bx_get('acl_card', 'post') > 0));
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
    	$mixedProfileId = $this->mixedProfileId;
    	if(bx_get('profile_id') !== false)
        	$mixedProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        $oAcl = BxDolAcl::getInstance();
        if (!$oAcl)
            return;

		$bBulk = is_array($mixedProfileId);
		if(!$bBulk) {
			$aProfileAclLevel = $oAcl->getMemberMembershipInfo($mixedProfileId);
			$this->setSelected('', $aProfileAclLevel['id']);
		}

		$aAclLevels = $oAcl->getMembershipsBy(array('type' => 'all_active_not_automatic_pair'));
		foreach ($aAclLevels as $k => $s)
            $aAclLevels[$k] = _t($s);

        $aItems = array();
        foreach ($aAclLevels as $iId => $sTitle) {
            $aItems[] = array(
                'id' => $iId,
                'name' => $iId,
                'class' => '',
                'title' => $sTitle,
                'icon' => '',
                'link' => 'javascript:void(0);',
                'onclick' => "bx_set_acl_level(" . (!$bBulk ? $mixedProfileId : "'" . urlencode(serialize($mixedProfileId)) . "'") . ", {$iId}, " . bx_js_string("$(this).parents('.bx-popup-applied:first')") . ");"
            );
        }

        $this->_aObject['menu_items'] = $aItems;
    }

    protected function setMembership($mixedProfileId, $iAclLevelId, $bAclCard = false)
    {
        bx_import('BxDolAcl');

        if(!is_array($mixedProfileId))
            $mixedProfileId = array($mixedProfileId);

		$iPerformerId = bx_get_logged_profile_id();
        $aCheck = checkActionModule($iPerformerId, 'set acl level', 'system', false);
        if(!isAdmin() && $aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return array('code' => 1, 'msg' => $aCheck[CHECK_ACTION_MESSAGE]);

        $iSet = 0;
        $aCards = array();
        $oAcl = BxDolAcl::getInstance();
        foreach($mixedProfileId as $iProfileId) {
            if(!$oAcl->setMembership($iProfileId, $iAclLevelId, 0, true))
                continue;

            $iSet += 1;
            if($bAclCard)
                $aCards[$iProfileId] = $oAcl->getProfileMembership($iProfileId);

            checkActionModule($iPerformerId, 'set acl level', 'system', true); // perform action
        }

        if(count($mixedProfileId) != $iSet)
            return array('code' => 2, 'msg' => _t('_error occured'));
        
        
        $aResult = array('code' => 0);
        if($bAclCard)
            $aResult['card'] = $aCards;

        return $aResult;
    }
}

/** @} */
