<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Polls module
 */
class BxPollsModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * ACTION METHODS
     */
    public function actionGetBlock()
    {
        $iContentId = (int)bx_get('content_id');
        $sBlock = bx_process_input(bx_get('block'));

        $sMethod = 'serviceGetBlock' . bx_gen_method_name($sBlock);
        if(!method_exists($this, $sMethod))
            return echoJson(array());

        $aBlock = $this->$sMethod($iContentId);
        if(empty($aBlock) || !is_array($aBlock))
            return echoJson(array());

        return echoJson(array(
        	'content' => $aBlock['content']
        ));
    }


    /**
     * SERVICE METHODS
     */
    public function serviceGetBlockSubentries($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        if($this->_oDb->isPerformed($iContentId, bx_get_logged_profile_id()))
            return $this->serviceGetBlockResults($iContentId);

        return $this->_serviceTemplateFunc('entrySubentries', $iContentId);
    }

    public function serviceGetBlockResults($iContentId = 0)
    {
        return $this->_serviceTemplateFunc('entryResults', $iContentId);
    }

    /**
     * PERMISSION METHODS
     */
    public function isAllowedVote($isPerformAction = false)
    {
        $aCheck = checkActionModule($this->_iProfileId, 'vote entry', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }


	/**
     * INTERNAL METHODS
     */
    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        return array();
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo)
    {
        $aBlock = $this->_oTemplate->entrySubentries($aContentInfo);

        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo);
        $aResult['title'] = $this->_oConfig->getTitle($aContentInfo);
        $aResult['text'] = '';
        $aResult['raw'] = $this->_oTemplate->getJsCode('entry') . $aBlock['content'];

        $this->_oTemplate->addJs(array('entry.js'));
        $this->_oTemplate->addCss(array('entry.css'));

        return $aResult;
    }
}

/** @} */
