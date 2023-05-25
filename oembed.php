<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$aLinks = bx_get('l');

bx_import('BxDolEmbed');
$oEmbed = BxDolEmbed::getObjectInstance('sys_iframely');

if (bx_get('html')){
    echo '<style>body, html {margin:0px}</style><div style="max-width:900; margin:0px auto; justify-content: center;" id="ifr">' . $oEmbed->getDataHtml($aLinks, bx_get('theme')) . '<script language="javascript" src="'.BX_DOL_URL_ROOT.'/plugins_public/jquery/jquery.min.js"></script><script>
    $(document).ready(function () {
        a = ["' . bx_get('hash') . '",  $("#ifr").height(),$("#ifr").width()];
        try{
            window.parent.postMessage(JSON.stringify(a), "*");
            window.ReactNativeWebView.postMessage(JSON.stringify(a));
         }
         catch(){}
    })
    window.addEventListener("message", function(event) {
        if (event.data && event.data!=""){
            try {
            a = JSON.parse(event.data);
                    } catch (e) {
            console.error("Failed to parse JSON:", event.data);
            throw e;
        }
            if (a && a.method=="resize"){
                a = ["' . bx_get('hash') . '",  a.height,$(".iframely-embed").width()];
                try{
                window.parent.postMessage(JSON.stringify(a), "*");
                window.ReactNativeWebView.postMessage(JSON.stringify(a));
                }
                catch(){}  
            }
        }
       
    }, false);
    
  </script></div>';
    exit();
}
echoJson($oEmbed->parseLinks($aLinks));