-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_googlecon@modules/boonex/google_connect/|std-icon.svg' WHERE `name`='bx_googlecon';


-- PAGES
UPDATE `sys_objects_page` SET `override_class_file`='modules/boonex/google_connect/classes/BxGoogleConPage.php' WHERE `object`='bx_googlecon_error';


-- ALERTS
UPDATE `sys_alerts_handlers` SET `file`='modules/boonex/google_connect/classes/BxGoogleConAlerts.php' WHERE `name`='bx_googlecon';