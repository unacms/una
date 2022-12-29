<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTimelineMenuItemActions.php');

/**
 * 'Item' menu.
 */
class BxTimelineMenuItemCounters extends BxTimelineMenuItemActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sMode = self::$_sModeCounters;
        $this->_bShowCounters = true;

        $this->_aItem2CheckFunc = array_merge($this->_aItem2CheckFunc, array(
            'item-vote' => 'isAllowedVoteView',
            'item-reaction' => 'isAllowedReactionView',
        ));
    }

    protected function _getMenuItemItemComment($aItem)
    {
        if(!isset($this->_aEvent['comments']) || !is_array($this->_aEvent['comments']) || !isset($this->_aEvent['comments']['system'])) 
            return false;

        $sCommentsSystem = $this->_aEvent['comments']['system'];
        $iCommentsObject = $this->_aEvent['comments']['object_id'];
        $aCommentsParams = array(
            'overwrite_counter_link_href' => 'javascript:void(0)',
            'overwrite_counter_link_onclick' => bx_replace_markers('{comment_onclick}', $this->_aMarkers),
            'show_do_comment_label' => $this->_bShowTitles,
            'show_counter' => $this->_bShowCounters,
            'show_counter_empty' => $this->_bShowCountersEmpty,
            'show_counter_label_icon' => true,
            'dynamic_mode' => $this->_bDynamicMode,
        );

        switch($this->_sMode) {
            case self::$_sModeActions:
                $sCommentsMethod = 'getElementInline';
                break;

            case self::$_sModeCounters:
                $sCommentsMethod = 'getCounter';
                break;
        }

        return $this->_oModule->getCmtsObject($sCommentsSystem, $iCommentsObject)->$sCommentsMethod($aCommentsParams);
    }
}

/** @} */
