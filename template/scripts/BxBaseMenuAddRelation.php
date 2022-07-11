<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu to add relation between profiles.
 */
class BxBaseMenuAddRelation extends BxTemplMenu
{
    protected $_sConnection;

    protected $_iInitiator;
    protected $_iContent;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sConnection = 'sys_profiles_relations';

        $this->_iInitiator = bx_get_logged_profile_id();
        $this->_iContent = 0;
    }

    public function getCode ()
    {
        if(!BxDolRelation::isEnabled())
            return '';

        return parent::getCode();
    }

    public function getMenuItems ()
    {
        $this->loadData();

        return parent::getMenuItems();
    }

    protected function _getMenuItem ($a)
    {
        $aResult = parent::_getMenuItem($a);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        if(!empty($a['class']))
            $aResult['class_add'] .= ' ' . $a['class'];

        return $aResult;
    }

    protected function loadData()
    {
        $sClassHidden = 'bx-menu-add-relation-hidden';

        if(bx_get('profile_id') !== false)
            $this->_iContent = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        $aSuggestions = array();
        $aRelations = BxDolConnection::getObjectInstance($this->_sConnection)->getRelations($this->_iInitiator, $this->_iContent, $aSuggestions);
        $bSuggestions = !empty($aSuggestions) && is_array($aSuggestions);

        $aItems = array();
        foreach($aRelations as $iId => $aRelation) {
            $aItems[] = array(
                'id' => $iId,
                'name' => $iId,
                'class' => $bSuggestions && !in_array($iId, $aSuggestions) ? $sClassHidden : '',
                'title' => _t($aRelation[BX_DATA_VALUES_DEFAULT]),
                'icon' => '',
                'link' => 'javascript:void(0);',
                'onclick' => "bx_conn_action(this, '" . $this->_sConnection . "', 'add', " . bx_js_string(json_encode(array('content' => $this->_iContent, 'relation' => $iId))) . ")"
            );
        }

        if($bSuggestions)
            $aItems[] = array(
                'id' => 'see_more',
                'name' => 'see_more',
                'class' => '',
                'title' => _t('_see_more'),
                'icon' => '',
                'link' => 'javascript:void(0);',
                'onclick' => bx_js_string("$(this).parents('li:first').hide().siblings('." . $sClassHidden . "').removeClass('" . $sClassHidden . "');")
            );

        $this->_aObject['menu_items'] = $aItems;
    }
}

/** @} */
