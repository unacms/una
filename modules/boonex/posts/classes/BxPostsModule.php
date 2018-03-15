<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Posts module
 */
class BxPostsModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
             $CNF['FIELD_PUBLISHED']
        ));
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        return $aResult;
    }

    public function onPublished($iContentId)
    {
		$CNF = &$this->_oConfig->CNF;

		$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return MsgBox(_t('_sys_txt_error_occured'));

		$aParams = array('object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]);
		if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
			$aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        bx_alert($this->getName(), 'added', $iContentId, false, $aParams);

        return '';
    }
}

/** @} */
