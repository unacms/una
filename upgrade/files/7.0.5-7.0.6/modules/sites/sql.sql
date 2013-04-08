
UPDATE `sys_options` SET `VALUE` = 'http://images.shrinktheweb.com/xino.php' WHERE `Name` = 'bx_sites_thumb_url' AND `VALUE` = 'http://www.shrinktheweb.com/xino.php';
UPDATE `sys_options` SET `VALUE` = '' WHERE `Name` = 'bx_sites_thumb_access_key' AND `VALUE` = 'cab8dee019304a3';
UPDATE `sys_options` SET `VALUE` = '' WHERE `Name` = 'bx_sites_thumb_pswd' AND `VALUE` = '65e44';
UPDATE `sys_options` SET `desc` = 'Access Key' WHERE `Name` = 'bx_sites_thumb_access_key' AND `desc` = 'Access key id';
UPDATE `sys_options` SET `desc` = 'Secret Key' WHERE `Name` = 'bx_sites_thumb_pswd' AND `desc` = 'Password';

UPDATE `sys_modules` SET `version` = '1.0.6' WHERE `uri` = 'sites' AND `version` = '1.0.5';

