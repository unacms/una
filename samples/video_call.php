<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreSamples Samples
 * @{
 */

/**
 * @page samples
 * @section test Test
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ('Video Call');
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();
?>

<!-- control jitsi from js -->
<div class="bx-clearfix"> 
    <a href="javascript:void(0);" class="bx-btn bx-def-margin-right" onclick="bx_mobile_apps_post_message({video_call_start:{uri:'aa2dd3'}})">VideoCall</a>
    <a href="javascript:void(0);" class="bx-btn bx-def-margin-right" onclick="bx_mobile_apps_post_message({video_call_start:{uri:'aa2dd3', audio:true}})">AudioCall</a>
    <a href="javascript:void(0);" class="bx-btn" onclick="bx_mobile_apps_post_message({video_call_stop:true})">EndCall</a>
</div>

<!-- receive events from jitsi -->
<script>
        if (typeof window.glBxVideoCallWillJoin === 'undefined')
            window.glBxVideoCallWillJoin = [];
        window.glBxVideoCallWillJoin.push(function (e) {
            $('#video_log').append('<div>VideoCallWillJoin</div>');
        });

        if (typeof window.glBxVideoCallJoined === 'undefined')
            window.glBxVideoCallJoined = [];
        window.glBxVideoCallJoined.push(function (e) {
            $('#video_log').append('<div>VideoCallJoined</div>');
        });

        if (typeof window.glBxVideoCallTerminated === 'undefined')
            window.glBxVideoCallTerminated = [];
        window.glBxVideoCallTerminated.push(function (e) {
            $('#video_log').append('<div>VideoCallTerminated</div>');
        });
</script>
<div id="video_log" class="bx-def-border bx-def-margin-top" style="min-height:100px; overflow:auto;"></div>

<?php
    return DesignBoxContent("Video Call", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
