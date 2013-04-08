
UPDATE `sys_email_templates` SET `Desc` = 'Group invitation template' WHERE `Name` = 'bx_groups_invitation' AND `Desc` = 'Events invitation template';

UPDATE `sys_modules` SET `version` = '1.0.5' WHERE `uri` = 'groups' AND `version` = '1.0.4';

