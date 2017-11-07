<?php
/**
* Note: This file is intended to be publicly accessible.
*/

$sPattern = '/^[A-Za-z0-9\.\-]+$/';

$sName = '';
if(!empty($_GET['bx_name']) && preg_match($sPattern, $_GET['bx_name']))
    $sName = $_GET['bx_name'];

$sShortName = '';
if(!empty($_GET['bx_short_name']) && preg_match($sPattern, $_GET['bx_short_name']))
    $sShortName = $_GET['bx_short_name'];

header("Content-Type: application/json");
header("X-Robots-Tag: none");
?>
{
  "name": "<?php echo $sName; ?>",
  "short_name": "<?php echo (!empty($sShortName) ? $sShortName : $sName); ?>",
  "start_url": "/",
  "display": "standalone",
  "gcm_sender_id": "482941778795",
  "DO_NOT_CHANGE_GCM_SENDER_ID": "Do not change the GCM Sender ID"
}