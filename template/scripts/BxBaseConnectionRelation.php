<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxBaseConnection
 */
class BxBaseConnectionRelation extends BxDolConnectionRelation
{
    protected function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->_aT = array_merge($this->_aT, [
            'do_initiator' => '_sys_conn_relations_do_initiator',
            'do_initiator_done' => '_sys_conn_relations_do_initiator_done',
            'do_initiator_undo' => '_sys_conn_relations_do_initiator_undo',
            'counter' => '_sys_conn_relations_counter'
        ]);
    }

    protected function _getActions($iInitiator, $iContent, $aParams = [])
    {
        $sName = $sTitle = '';
        $aActions = [];

        if($this->isConnected($iInitiator, $iContent)) {
            $sName = 'remove';
            $sTitle = $this->_aT['do_initiator_done'];
            $aActions = [[
                'name' => 'remove',
                'title' => ($sKey = 'txt_unsubscribe') && !empty($aParams[$sKey]) ? $aParams[$sKey] : $this->_aT['do_initiator_undo']
            ], [
                /*
                 * An empty array item to show all items in popup.
                 * More actions will be added later.
                 */
            ]];
        } 
        else {
            $sName = 'add';
            $sTitle = $this->_aT['do_initiator'];
            $aActions = [];

            $aSuggestions = [];
            $aRelations = $this->getRelations($iInitiator, $iContent, $aSuggestions);
            $bSuggestions = !empty($aSuggestions) && is_array($aSuggestions);

            $sJsObject = $this->getJsObjectName($iContent, $aParams);
            $sClassHidden = 'bx-menu-add-relation-hidden';
            foreach($aRelations as $iId => $aRelation) {
                $aActions[] = [
                    'id' => 'bx-conn-rl-' . $iId,
                    'name' => 'bx-conn-rl-' . $iId,
                    'class_add' => $bSuggestions && !in_array($iId, $aSuggestions) ? $sClassHidden : '',
                    'title' => _t($aRelation[BX_DATA_VALUES_DEFAULT]),
                    'icon' => '',
                    'link' => 'javascript:void(0);',
                    'onclick' => $sJsObject . ".connect(this, 'add', " . bx_js_string(json_encode(['content' => $iContent, 'relation' => $iId])) . ")"
                ];
            }

            if($bSuggestions)
                $aActions[] = [
                    'id' => 'see_more',
                    'name' => 'see_more',
                    'title' => _t('_see_more'),
                    'icon' => '',
                    'link' => 'javascript:void(0);',
                    'onclick' => bx_js_string("$(this).parents('li:first').hide().siblings('." . $sClassHidden . "').removeClass('" . $sClassHidden . "');")
                ];
        }

        return [
            'name' => $sName,
            'title' => $sTitle,
            'items' => $aActions
        ];
    }
    
    protected function _getActionIconAsIcon($sAction)
    {
        $sDefault = 'sync';

        $aA2I = [
            'add' => $sDefault,
            'remove' => $sDefault
        ];

        return isset($aA2I[$sAction]) ? $aA2I[$sAction] : $sDefault;
    }

    protected function _getActionIconAsEmoji($sAction)
    {
        $sDefault = '🔁';

        $aA2I = [
            'add' => $sDefault,
            'remove' => $sDefault
        ];

        return isset($aA2I[$sAction]) ? $aA2I[$sAction] : $sDefault;
    }

    protected function _getActionIconAsImage($sAction)
    {
        $sDefault = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw-icon lucide-refresh-cw"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>';

        $aA2I = [
            'add' => $sDefault,
            'remove' => $sDefault
        ];

        return isset($aA2I[$sAction]) ? $aA2I[$sAction] : $sDefault;
    }
}
