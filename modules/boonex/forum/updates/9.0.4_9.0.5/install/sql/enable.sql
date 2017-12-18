SET @sName = 'bx_forum';


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_featured";s:6:"params";a:4:{i:0;s:5:"table";i:1;b:0;i:2;b:1;i:3;b:0;}}' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_featured_entries';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:13:"empty_message";b:0;}}}' WHERE `module`=@sName AND `title`='_bx_forum_page_block_title_my_entries';


-- ACL
SET @iIdActionSetThumb = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='set thumb' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionSetThumb;
DELETE FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='set thumb';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'set thumb', NULL, '_bx_forum_acl_action_set_thumb', '', 1, 3);
SET @iIdActionSetThumb = LAST_INSERT_ID();

SET @iStandard = 3;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionSetThumb),
(@iModerator, @iIdActionSetThumb),
(@iAdministrator, @iIdActionSetThumb),
(@iPremium, @iIdActionSetThumb);
