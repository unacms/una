<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolAlerts');

class BxTimelineResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    /**
     * Constructor
     * @param BxTimelineModule $oModule - an instance of current module
     */
    public function __construct($oModule)
    {
        parent::BxDolAlertsResponse();

        $this->_oModule = $oModule;
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        if(is_array($oAlert->aExtras) && isset($oAlert->aExtras['from_wall']) && (int)$oAlert->aExtras['from_wall'] == 1)
        	$this->_responseInner($oAlert);
		else
			$this->_responseOuter($oAlert);
    }

    protected function _responseOuter($oAlert)
    {
    	$iPrivacyView = $this->_getPrivacyView($oAlert->aExtras);
    	if($iPrivacyView == BX_DOL_PG_HIDDEN)
    		return;

		$aHandler = $this->_oModule->_oConfig->getHandlers($oAlert->sUnit . '_' . $oAlert->sAction);
		switch($aHandler['type']) {
			case BX_TIMELINE_HANDLER_TYPE_INSERT:
				$sContent = !empty($oAlert->aExtras) && is_array($oAlert->aExtras) ? serialize(bx_process_input($oAlert->aExtras)) : '';

				$iId = $this->_oModule->_oDb->insertEvent(array(
		            'owner_id' => $oAlert->iSender,
		            'type' => $oAlert->sUnit,
		            'action' => $oAlert->sAction,
		        	'object_id' => $oAlert->iObject,
		        	'object_privacy_view' => $iPrivacyView,
		            'content' => $sContent,
					'title' => '',
					'description' => ''
		        ));

		        if(!empty($iId))
		        	$this->_oModule->onUpdate($oAlert->iSender);

		        $this->_oModule->_oDb->updateSimilarObject($iId, $oAlert);		
		        break;

			case BX_TIMELINE_HANDLER_TYPE_UPDATE:
				$sContent = !empty($oAlert->aExtras) && is_array($oAlert->aExtras) ? serialize(bx_process_input($oAlert->aExtras)) : '';

				$this->_oModule->_oDb->updateEvent(array('object_privacy_view' => $iPrivacyView, 'content' => $sContent), array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
				break;

			case BX_TIMELINE_HANDLER_TYPE_DELETE:
				$this->_oModule->_oDb->deleteEvent(array('type' => $oAlert->sUnit, 'object_id' => $oAlert->iObject));
				break;
		}
    }

    //TODO: Remove if it's not used. 
    protected function _responseInner($oAlert)
    {
    	$this->_oModule->_iOwnerId = (int)$oAlert->aExtras['owner_id'];
        $sMedia = strtolower(str_replace('bx_', '', $oAlert->sUnit));
		$aMediaInfo = $this->_oModule->_oTemplate->getCommonMedia($sMedia, $oAlert->iObject);

		$iId = $this->_oModule->_oDb->insertEvent(array(
            'owner_id' => $this->_oModule->_iOwnerId,
            'type' => $this->_oModule->_oConfig->getCommonPostPrefix() . $sMedia,
            'action' => '',
			'object_id' => $this->_oModule->_getUserId(),
			'object_privacy_view' => $this->_getPrivacyView($oAlert->aExtras),
            'content' => serialize(array(
            	'type' => $sMedia, 
            	'id' => $oAlert->iObject,
			)),
			'title' => bx_process_input($aMediaInfo['title']), 
			'description' => bx_process_input($aMediaInfo['description'])
        ));

        if(!empty($iId))
			$this->_oModule->onUpdate($this->_oModule->_iOwnerId);

		echo $this->_oModule->_oTemplate->_wrapInTagJsCode("parent." . $this->_oModule->_sJsPostObject . "._getPost(null, " . $iId . ");");
    }

    protected function _getPrivacyView($aExtras)
    {
    	return is_array($aExtras) && isset($aExtras['privacy_view']) ? (int)$aExtras['privacy_view'] : $this->_oModule->_oConfig->getPrivacyViewDefault();
    }
}

/** @} */ 
