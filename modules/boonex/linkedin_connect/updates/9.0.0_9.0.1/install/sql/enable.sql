-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_linkedin@modules/boonex/linkedin_connect/|std-icon.svg' WHERE `name`='bx_linkedin';


-- PAGES
UPDATE `sys_objects_page` SET `override_class_file`='modules/boonex/linkedin_connect/classes/BxLinkedinPage.php' WHERE `object`='bx_linkedin_error';


-- ALERTS
UPDATE `sys_alerts_handlers` SET `file`='modules/boonex/linkedin_connect/classes/BxLinkedinAlerts.php' WHERE `name`='bx_linkedin';