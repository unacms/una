<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuCreatePost extends BxTemplMenuCustom
{
    protected $_sJsObject;

    protected $_mixedContextId;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        $this->_aObject['menu_id'] = 'sys-create-post-menu';

        $this->_bShowDivider = false;
        $this->_sJsObject = 'oBxDolCreatePost';

        $this->_mixedContextId = false;
    }

    public function setContextId($mixedContextId)
    {
        $this->_mixedContextId = $mixedContextId;
    }

    protected function getMenuItemsRaw ()
    {
    	$aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'all_pairs_name_uri', 'active' => 1));

        $aResult = array();
    	$aMenuItems = $this->_oQuery->getMenuItems();
    	foreach($aMenuItems as $iKey => $aMenuItem) {
            if((int)$aMenuItem['active'] == 0)
                continue;

            $sModule = $aMenuItem['module'];
            if(!isset($aModules[$sModule]))
                continue;

            if(BxDolRequest::serviceExists($sModule, 'act_as_profile'))
                continue;

            if($this->_mixedContextId !== false && ($aContextInfo = BxDolProfileQuery::getInstance()->getInfoById(abs($this->_mixedContextId))))
                if(bx_srv($aContextInfo['type'], 'check_allowed_post_in_profile', [$aContextInfo['content_id'], $sModule]) !== CHECK_ACTION_RESULT_ALLOWED)
                    continue;

            $aResult[$iKey] = array_merge($aMenuItem, array(
                'id' => $sModule,
                'name' => $sModule,
                'onclick' => "return " . $this->_sJsObject . ".getForm('" . $sModule . "', '" . $aModules[$sModule] . "', this)"
            ));
    	}

        if(!empty($aResult) && is_array($aResult))
            $aResult['more-auto'] = array(
                'module' => 'system', 
                'id' => 'more-auto', 
                'name' => 'more-auto',
                'title' => '_sys_menu_item_title_va_more_auto_empty', 
                'href' => 'javascript:void(0)', 
                'icon' => 'ellipsis-v',
                'active' => 1
            );

        return $aResult;
    }
}

/** @} */
