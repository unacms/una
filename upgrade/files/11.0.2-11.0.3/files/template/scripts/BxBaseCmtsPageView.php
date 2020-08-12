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
        
        $sSystem = bx_get('sys');
        $sSystem = $sSystem !== false ? $sSystem = bx_process_input($sSystem) : '';

        $iObjectId = bx_get('id');
        $iObjectId = $iObjectId !== false ? bx_process_input($iObjectId, BX_DATA_INT) : 0;

        $iCommentId = bx_get('cmt_id');
        $iCommentId = $iCommentId !== false ? bx_process_input($iCommentId, BX_DATA_INT) : 0;

        if(!$sSystem || !$iObjectId)
            return;

        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iObjectId, true);
        if(!$oCmts)
            return;

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
