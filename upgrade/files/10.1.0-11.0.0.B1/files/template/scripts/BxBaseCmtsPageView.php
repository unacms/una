<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseCmtsPageView extends BxTemplPage
{
    public function __construct($aObject, $oTemplate)
    {
        parent::__construct($aObject, $oTemplate);
        
        if(empty($sSystem) && ($sSystem = bx_get('sys')) !== false)
            $sSystem = bx_process_input($sSystem);

        if(empty($iObjectId) && ($iObjectId = bx_get('id')) !== false)
            $iObjectId = bx_process_input($iObjectId, BX_DATA_INT);

        if(empty($iCommentId) && ($iCommentId = bx_get('cmt_id')) !== false)
            $iCommentId = bx_process_input($iCommentId, BX_DATA_INT);

        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iObjectId, true);

        $sObjectTitle = bx_process_output(strip_tags($oCmts->getObjectTitle($iObjectId)));
        $sObjectUrl = $oCmts->getBaseUrl();

        $this->addMarkers(array(
            'system' => $sSystem,
            'object_id' => $iObjectId,
            'object_title' => $sObjectTitle,
            'object_url' => $sObjectUrl,
            'comment_id' => $iCommentId
        ));
    }
}

/** @} */
