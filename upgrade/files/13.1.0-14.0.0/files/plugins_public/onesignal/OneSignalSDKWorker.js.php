<?php
header("Service-Worker-Allowed: /");
header("Content-Type: application/javascript");
header("X-Robots-Tag: none");
?>
importScripts("https://cdn.onesignal.com/sdks/OneSignalSDKWorker.js");
