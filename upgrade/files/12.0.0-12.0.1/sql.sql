
UPDATE `sys_options` SET `value` = '300' WHERE `name` = 'sys_default_curl_timeout' AND `value` = '10';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.0.1' WHERE (`version` = '12.0.0') AND `name` = 'system';

