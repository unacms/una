-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_twitter@modules/boonex/twitter_connect/|std-icon.svg' WHERE `name`='bx_twitter';


-- PAGES
UPDATE `sys_objects_page` SET `override_class_file`='modules/boonex/twitter_connect/classes/BxTwitterPage.php' WHERE `object`='bx_twitter_error';


-- ALERTS
UPDATE `sys_alerts_handlers` SET `file`='modules/boonex/twitter_connect/classes/BxTwitterAlerts.php' WHERE `name`='bx_twitter';