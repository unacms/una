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
    }

    protected function _getMenuItemItemComment($aItem)
    {
        if(!isset($this->_aEvent['comments']) || !is_array($this->_aEvent['comments']) || !isset($this->_aEvent['comments']['system'])) 
            return false;

        $sCommentsSystem = $this->_aEvent['comments']['system'];
        $iCommentsObject = $this->_aEvent['comments']['object_id'];
        $aCommentsParams = array('dynamic_mode' => $this->_bDynamicMode);

        switch($this->_sMode) {
            case self::$_sModeActions:
                $sCommentsMethod = 'getElementInline';
                $aCommentsParams['show_do_comment_label'] = $this->_bShowTitles;
                $aCommentsParams['show_counter'] = $this->_bShowCounters;
                break;

            case self::$_sModeCounters:
                $sCommentsMethod = 'getCounter';
                $aCommentsParams['show_counter'] = true;
                break;
        }

        return $this->_oModule->getCmtsObject($sCommentsSystem, $iCommentsObject)->$sCommentsMethod($aCommentsParams);
    }
}

/** @} */
