
UPDATE `sys_objects_actions` SET `Script` = 'window.open(''{moduleUrl}get_image/original/{fileKey}.{fileExt}'')', `Url` = '' WHERE `Type` = '[db_prefix]' AND `Url` = '{moduleUrl}get_image/original/{fileKey}.{fileExt}' AND `Script` = '';

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'photos' AND `version` = '1.0.2';

