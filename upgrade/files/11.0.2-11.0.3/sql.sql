
ALTER TABLE `sys_options` CHANGE `type` `type` ENUM( 'value', 'digit', 'text', 'checkbox', 'select', 'combobox', 'file', 'image', 'list', 'rlist', 'rgb', 'rgba', 'datetime' ) NOT NULL DEFAULT 'digit';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.3' WHERE (`version` = '11.0.2') AND `name` = 'system';
